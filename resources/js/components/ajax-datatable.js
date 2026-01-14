/**
 * ============================================================
 * AjaxDataTable
 * Maneja filtros, paginación, selección de filas y columnas
 * mediante AJAX sin recargar la página
 * ============================================================
 */
export default function AjaxDataTable(config) {

    /* ============================================================
     * 1. CONFIGURACIÓN INICIAL
     * ============================================================
     */
    const {
        tableId,
        formId,
        debounce = 500,
        chips = {}
    } = config;

    const table = document.getElementById(tableId);
    const form = document.getElementById(formId);
    const chipsContainer = document.getElementById('active-filters');

    // Si no existe la tabla o el formulario, no inicializamos nada
    if (!table || !form) return;

    let timer = null;


    /* ============================================================
     * 2. UTILIDADES
     * ============================================================
     */

    /**
     * Resuelve el label legible de un filtro
     * Puede venir desde:
     * - values definidos en config
     * - una fuente global (window.filterSources)
     */
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


    /* ============================================================
     * 3. SERIALIZACIÓN DEL FORMULARIO
     * ============================================================
     */

    /**
     * Obtiene los parámetros del formulario en un objeto
     * Maneja correctamente inputs tipo array (ej: columns[])
     */
    const getParams = () => {
        const formData = new FormData(form);
        const params = {};

        for (const [key, value] of formData.entries()) {
            if (value === '') continue;

            if (key.endsWith('[]')) {
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

    /**
     * Construye la URL final con query params
     */
    const buildUrl = (params) => {
        const base = window.location.pathname;
        const searchParams = new URLSearchParams();

        Object.entries(params).forEach(([key, value]) => {
            if (Array.isArray(value)) {
                value.forEach(v => searchParams.append(key, v));
            } else {
                searchParams.append(key, value);
            }
        });

        const query = searchParams.toString();
        return query ? `${base}?${query}` : base;
    };


    /* ============================================================
     * 4. PETICIÓN AJAX DE LA TABLA
     * ============================================================
     */

    /**
     * Hace fetch del HTML de la tabla
     * Aplica estados visuales de carga
     */

    let currentRequest = null;

    const fetchTable = async (url) => {
        // Evitar peticiones concurrentes si ya se está cargando
        if (currentRequest) {
            currentRequest.abort();
        }

        currentRequest = new AbortController();
        const { signal } = currentRequest;

        table.classList.add('opacity-50', 'pointer-events-none', 'cursor-wait');

        try {
            const res = await fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                signal
            });

            if (!res.ok) throw new Error('Error en la respuesta del servidor');

            table.innerHTML = await res.text();
            syncCheckboxes();

        } catch (error) {
            if (error.name !== 'AbortError') {
                console.error("DataTable Error:", error);
                alert("No se pudo cargar la información.");
            }
        } finally {
            table.classList.remove('opacity-50', 'pointer-events-none', 'cursor-wait');
        }
    };


    /* ============================================================
     * 5. CHIPS DE FILTROS ACTIVOS
     * ============================================================
     */

    /**
     * Renderiza los chips visibles de filtros activos
     */
    const renderChips = (params) => {
        if (!chipsContainer) return;

        chipsContainer.innerHTML = '';

        // Excluir parámetros que no deben generar chips
        const realFilterKeys = Object.keys(params).filter(key => 
            key !== 'per_page' && key !== 'columns[]'
        );

        // Chips individuales
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

        // Botón "Limpiar todo" solo si hay filtros reales
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


    /* ============================================================
     * 6. APLICAR FILTROS
     * ============================================================
     */

    /**
     * Aplica filtros:
     * - Serializa formulario
     * - Hace fetch
     * - Actualiza URL
     * - Renderiza chips
     */
    const apply = () => {
        const params = getParams();
        const url = buildUrl(params);
        fetchTable(url);
        history.pushState({}, '', url);
        renderChips(params);
    };


    /* ============================================================
     * 7. LIMPIEZA DE FILTROS
     * ============================================================
     */

    /**
     * Limpia filtros pero conserva:
     * - per_page
     * - columnas seleccionadas
     */
    const clearAll = () => {
        const currentPerPage = form.querySelector('[name="per_page"]')?.value || 10;

        const selectedColumns = Array.from(
            form.querySelectorAll('input[name="columns[]"]:checked')
        ).map(cb => cb.value);

        form.reset();

        const perPageInput = form.querySelector('[name="per_page"]');
        if (perPageInput) perPageInput.value = currentPerPage;

        form.querySelectorAll('input[name="columns[]"]').forEach(cb => {
            cb.checked = selectedColumns.includes(cb.value);
        });

        apply(); 
    };


/* ============================================================
    * 8. MANEJO DE COLUMNAS INTELIGENTE
    * ============================================================
    */
    const getDeviceDefaultColumns = (container) => {
        // Usamos 768px (md) o 640px (sm) según tu preferencia de diseño
        const isMobile = window.innerWidth < 640;
        const desktopCols = JSON.parse(container.dataset.defaultDesktop || '[]');
        const mobileCols = JSON.parse(container.dataset.defaultMobile || '[]');
        
        return isMobile ? mobileCols : desktopCols;
    };

    const resetColumns = () => {
        const container = document.getElementById('column-selector-container');
        if (!container || !form) return;
        
        const targetColumns = getDeviceDefaultColumns(container);
        const columnCheckboxes = form.querySelectorAll('input[name="columns[]"]');
        
        columnCheckboxes.forEach(cb => {
            cb.checked = targetColumns.includes(cb.value);
        });
        
        apply();
    };

    window.resetTableColumns = resetColumns;

    /**
     * Función interna para verificar si debemos forzar columnas de móvil al inicio
     */
    const checkInitialDeviceColumns = () => {
        const urlParams = new URLSearchParams(window.location.search);
        // SI no hay columnas en la URL AND estamos en móvil
        if (!urlParams.has('columns[]') && window.innerWidth < 640) {
            const container = document.getElementById('column-selector-container');
            if (container) {
                const targetColumns = getDeviceDefaultColumns(container);
                const columnCheckboxes = form.querySelectorAll('input[name="columns[]"]');
                
                columnCheckboxes.forEach(cb => {
                    cb.checked = targetColumns.includes(cb.value);
                });
                
                apply();
            }
        }
    };


    /* ============================================================
     * 9. AUTO-SUBMIT DEL FORMULARIO
     * ============================================================
     */

    // Selects
    form.querySelectorAll('select').forEach(el =>
        el.addEventListener('change', apply)
    );

    // Inputs texto con debounce
    form.querySelectorAll('input[type="text"]').forEach(el =>
        el.addEventListener('input', () => {
            clearTimeout(timer);
            timer = setTimeout(apply, debounce);
        })
    );

    // Checkboxes (incluye columnas)
    form.querySelectorAll('input[type="checkbox"]').forEach(el => 
        el.addEventListener('change', apply)
    );


    /* ============================================================
     * 10. PAGINACIÓN AJAX
     * ============================================================
     */
    table.addEventListener('click', e => {
        const link = e.target.closest('.pagination a');
        if (!link) return;

        e.preventDefault();
        fetchTable(link.href);
        history.pushState({}, '', link.href);
    });


    /* ============================================================
     * 11. SELECCIÓN DE FILAS (GLOBAL)
     * ============================================================
     */

    let selectedIds = [];

    /**
     * Sincroniza:
     * - Array global
     * - Checkbox maestro
     * - Evento global para Alpine
     */
    const updateSelectionState = () => {
        const allCheckboxes = Array.from(table.querySelectorAll('.row-checkbox'));
        const masterCheckbox = document.getElementById('select-all-main');

        // Sincronizar array de forma funcional
        allCheckboxes.forEach(cb => {
            const id = cb.value;
            const index = selectedIds.indexOf(id);
            
            if (cb.checked && index === -1) {
                selectedIds.push(id);
            } else if (!cb.checked && index !== -1) {
                selectedIds.splice(index, 1);
            }
        });

        if (masterCheckbox) {
            const visibleChecked = allCheckboxes.filter(cb => cb.checked).length;
            const totalVisible = allCheckboxes.length;

            masterCheckbox.checked = totalVisible > 0 && visibleChecked === totalVisible;
            masterCheckbox.indeterminate = visibleChecked > 0 && visibleChecked < totalVisible;
        }

        document.dispatchEvent(new CustomEvent('table-selection-changed', { 
            detail: { ids: [...selectedIds] } 
        }));
    }

    /**
     * Sincroniza checkboxes visibles con la selección global
     */
    const syncCheckboxes = () => {
        const allCheckboxes = table.querySelectorAll('.row-checkbox');

        allCheckboxes.forEach(cb => {
            cb.checked = selectedIds.includes(cb.value);
        });

        updateSelectionState();
    };


    /* ============================================================
     * 12. EVENTOS DE CHECKBOXES DE LA TABLA
     * ============================================================
     */
    table.addEventListener('change', (e) => {

        // Checkbox maestro
        if (e.target.id === 'select-all-main') {
            const isChecked = e.target.checked;
            const checkboxes = table.querySelectorAll('.row-checkbox');

            checkboxes.forEach(cb => {
                cb.checked = isChecked;
            });

            updateSelectionState();
            return;
        }

        // Checkbox individual
        if (e.target.classList.contains('row-checkbox')) {
            updateSelectionState();
        }
    });


    /* ============================================================
     * 13. LIMPIAR SELECCIÓN GLOBAL
     * ============================================================
     */

    const clearGlobalSelection = () => {
        selectedIds = [];
        table.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = false);
        updateSelectionState();
    };

    window.clearTableSelection = clearGlobalSelection;


    /* ============================================================
     * 14. INICIALIZACIÓN
     * ============================================================
     */
    // Ejecutar detección de dispositivo inmediatamente
    checkInitialDeviceColumns();
    
    // Render de chips inicial
    renderChips(getParams());
}
