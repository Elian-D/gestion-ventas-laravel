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
        return Object.fromEntries(
            [...new FormData(form).entries()].filter(([_, v]) => v !== '')
        );
    };

    const buildUrl = (params) => {
        const base = window.location.pathname;
        const query = new URLSearchParams(params).toString();
        return query ? `${base}?${query}` : base;
    };

    const fetchTable = async (url) => {
        table.classList.add('opacity-50', 'pointer-events-none', 'cursor-wait');

        const res = await fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });

        table.innerHTML = await res.text();
        table.classList.remove('opacity-50', 'pointer-events-none', 'cursor-wait');
    };

    const renderChips = (params) => {
        if (!chipsContainer) return;

        chipsContainer.innerHTML = '';

        Object.entries(params).forEach(([key, value]) => {
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

        if (!Object.keys(params).length) return;
        {
            const clear = document.createElement('button');
            clear.textContent = 'Limpiar todo';
            clear.className = 'text-xs text-red-500 ml-2';
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
    form.reset();

    const base = window.location.pathname;

    fetchTable(base);

    // ⚠️ CLAVE: reemplaza el estado, no lo empujes
    history.replaceState({}, '', base);

    renderChips({});
};


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

    // Paginación
    table.addEventListener('click', e => {
        const link = e.target.closest('.pagination a');
        if (!link) return;

        e.preventDefault();
        fetchTable(link.href);
        history.pushState({}, '', link.href);
    });

    renderChips(getParams());
}
