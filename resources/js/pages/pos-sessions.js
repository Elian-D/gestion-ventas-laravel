import AjaxDataTable from '../components/ajax-datatable/index';

document.addEventListener('DOMContentLoaded', () => {
    AjaxDataTable({
        tableId: 'pos-sessions-table',
        formId: 'pos-sessions-filters',
        debounce: 800,
        chips: {
            // Filtro por Terminal
            terminal_id: {
                label: 'Terminal',
                source: 'terminals' // window.filterSources.terminals
            },
            // Filtro por Cajero
            user_id: {
                label: 'Cajero(a)',
                source: 'users' // window.filterSources.users
            },
            // Filtro por Estado (Abierta/Cerrada)
            status: {
                label: 'Estado',
                source: 'statuses' // window.filterSources.statuses
            },
            // Filtros de Fecha de Apertura
            from_date: { 
                label: 'Desde',
                format: (val) => val ? val.replace('T', ' ') : '' 
            },
            to_date: { 
                label: 'Hasta',
                format: (val) => val ? val.replace('T', ' ') : '' 
            }
        }
    });
});