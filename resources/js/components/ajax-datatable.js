export default function AjaxDataTable(config) {

    const {
        tableId,
        formId,
        debounce = 500,
        chips = {}
    } = config;

    const table = document.getElementById(tableId);
    const form = document.getElementById(formId);
    const chipsContainer = document.getElementById('active-filters');

    if (!table || !form) return;

    let timer = null;

    const resolveLabel = (config, value) => {
        if (!config) return value;

        if (config.values) {
            return config.values[value] ?? value;
        }

        if (config.source && window.filterSources?.[config.source]) {
            return window.filterSources[config.source][value] ?? value;
        }

        return value;
    };


    const getParams = () => {
        const formData = new FormData(form);
        const params = {};

        for (const [key, value] of formData.entries()) {
            if (value === '') continue;

            if (key.endsWith('[]')) {
                // Si la llave ya existe, añadimos al array, si no, lo creamos
                if (!params[key]) {
                    params[key] = [];
                }
                params[key].push(value);
            } else {
                params[key] = value;
            }
        }
        return params;
    };

    const buildUrl = (params) => {
        const base = window.location.pathname;
        const searchParams = new URLSearchParams();

        Object.entries(params).forEach(([key, value]) => {
            if (Array.isArray(value)) {
                // Si es un array (como columns[]), añadimos cada valor individualmente
                value.forEach(v => searchParams.append(key, v));
            } else {
                searchParams.append(key, value);
            }
        });

        const query = searchParams.toString();
        return query ? `${base}?${query}` : base;
    };

    const fetchTable = async (url) => {
        table.classList.add('opacity-50', 'pointer-events-none', 'cursor-wait');

        const res = await fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });

        table.innerHTML = await res.text();
        syncCheckboxes();
        table.classList.remove('opacity-50', 'pointer-events-none', 'cursor-wait');
    };

    const renderChips = (params) => {
        if (!chipsContainer) return;

        chipsContainer.innerHTML = '';

        // 1. Filtramos los parámetros para obtener solo los que generan chips reales
        const realFilterKeys = Object.keys(params).filter(key => 
            key !== 'per_page' && key !== 'columns[]'
        );

        // 2. Renderizamos los chips normalmente
        realFilterKeys.forEach(key => {
            const value = params[key];
            const config = chips[key] ?? {};
            const name = config.label ?? key;
            const label = resolveLabel(config, value);

            const chip = document.createElement('button');
            chip.type = 'button';
            chip.className = `
                inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold
                rounded-full bg-indigo-50 text-indigo-700 border border-indigo-100
                hover:bg-indigo-100 transition
            `;

            chip.innerHTML = `${name}: ${label} ✕`;

            chip.onclick = () => {
                const input = form.querySelector(`[name="${key}"]`);
                if (input) input.value = '';
                apply();
            };

            chipsContainer.appendChild(chip);
        });

        // 3. LA CLAVE: Solo mostramos "Limpiar todo" si hay filtros reales aplicados
        if (realFilterKeys.length > 0) {
            const clear = document.createElement('button');
            clear.textContent = 'Limpiar todo';
            clear.className = 'text-xs text-red-500 hover:text-red-700 ml-2 font-medium transition';
            clear.onclick = () => {
                clearAll();
            };
            chipsContainer.appendChild(clear);
        }
    };

    const apply = () => {
        const params = getParams();
        const url = buildUrl(params);
        fetchTable(url);
        history.pushState({}, '', url);
        renderChips(params);
    };

    const clearAll = () => {
        // 1. Guardamos el estado actual de las preferencias (per_page y columnas)
        const currentPerPage = form.querySelector('[name="per_page"]')?.value || 10;
        
        // Obtenemos los valores de las columnas que están marcadas actualmente
        const selectedColumns = Array.from(form.querySelectorAll('input[name="columns[]"]:checked'))
            .map(cb => cb.value);
        
        // 2. Reseteamos el formulario (esto desmarca todo y limpia inputs)
        form.reset();
        
        // 3. Restauramos per_page
        const perPageInput = form.querySelector('[name="per_page"]');
        if (perPageInput) perPageInput.value = currentPerPage;
        
        // 4. Restauramos las columnas marcadas
        form.querySelectorAll('input[name="columns[]"]').forEach(cb => {
            cb.checked = selectedColumns.includes(cb.value);
        });

        // 5. Aplicamos los cambios
        apply(); 
    };

    const resetColumns = () => {
        const container = document.getElementById('column-selector-container');
        if (!container) return;

        // 1. Obtenemos las columnas por defecto desde el atributo data
        const defaultColumns = JSON.parse(container.dataset.defaultColumns || '[]');
        
        // 2. Buscamos todos los checkboxes de columnas
        const columnCheckboxes = form.querySelectorAll('input[name="columns[]"]');
        
        columnCheckboxes.forEach(cb => {
            // 3. Lo marcamos solo si está en la lista de permitidos por defecto
            // Usamos el valor del checkbox para comparar
            cb.checked = defaultColumns.includes(cb.value);
        });

        // 4. Ejecutamos la petición AJAX para actualizar la tabla
        apply();
    };

    window.resetTableColumns = resetColumns;

    // Autosubmit select
    form.querySelectorAll('select').forEach(el =>
        el.addEventListener('change', apply)
    );

    // Autosubmit input (debounce)
    form.querySelectorAll('input[type="text"]').forEach(el =>
        el.addEventListener('input', () => {
            clearTimeout(timer);
            timer = setTimeout(apply, debounce);
        })
    );

        // Autosubmit checkboxes (Columnas)
    form.querySelectorAll('input[type="checkbox"]').forEach(el => 
        el.addEventListener('change', () => {
            // Opcional: Si es la paginación no reseteamos página, 
            // pero para columnas es indiferente.
            apply();
        })
    );

    // Paginación
    table.addEventListener('click', e => {
        const link = e.target.closest('.pagination a');
        if (!link) return;

        e.preventDefault();
        fetchTable(link.href);
        history.pushState({}, '', link.href);
    });

    let selectedIds = [];

    const updateSelectionState = () => {
        const allCheckboxes = table.querySelectorAll('.row-checkbox');
        const masterCheckbox = document.getElementById('select-all-main');
        
        // 1. Sincronizar array global con lo que el usuario acaba de marcar/desmarcar
        allCheckboxes.forEach(cb => {
            if (cb.checked) {
                if (!selectedIds.includes(cb.value)) selectedIds.push(cb.value);
            } else {
                selectedIds = selectedIds.filter(id => id !== cb.value);
            }
        });

        // 2. Sincronizar el checkbox maestro visualmente
        if (masterCheckbox) {
            const visibleChecked = table.querySelectorAll('.row-checkbox:checked').length;
            const totalVisible = allCheckboxes.length;

            masterCheckbox.checked = totalVisible > 0 && visibleChecked === totalVisible;
            // El estado indeterminado ocurre si hay algunos marcados pero no todos EN LA VISTA ACTUAL
            masterCheckbox.indeterminate = visibleChecked > 0 && visibleChecked < totalVisible;
        }

        // 3. ENVIAR SIEMPRE EL TOTAL GLOBAL (Esto arregla el contador del dropdown)
        document.dispatchEvent(new CustomEvent('table-selection-changed', { 
            detail: { ids: [...selectedIds] } 
        }));
    };

    const syncCheckboxes = () => {
        const allCheckboxes = table.querySelectorAll('.row-checkbox');
        
        // Marcar los que ya estaban seleccionados globalmente
        allCheckboxes.forEach(cb => {
            cb.checked = selectedIds.includes(cb.value);
        });

        // Re-ejecutar la lógica visual del maestro y el conteo del dropdown
        updateSelectionState();
    };

    table.addEventListener('change', (e) => {
        // Si se hace click en el maestro
        if (e.target.id === 'select-all-main') {
            const isChecked = e.target.checked;
            const checkboxes = table.querySelectorAll('.row-checkbox');
            
            checkboxes.forEach(cb => {
                cb.checked = isChecked;
            });
            // IMPORTANTE: updateSelectionState se encarga de añadir/quitar del array global
            updateSelectionState();
            return;
        }
        
        // Si se hace click en una fila individual
        if (e.target.classList.contains('row-checkbox')) {
            updateSelectionState();
        }
    });
    
    renderChips(getParams());
}
