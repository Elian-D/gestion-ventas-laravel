import AjaxDataTable from '../components/ajax-datatable/index';

document.addEventListener('DOMContentLoaded', () => {
    AjaxDataTable({
        tableId: 'journals-table',
        formId: 'journals-filters',
        debounce: 800,
        chips: {
            search: { label: 'Búsqueda' },
            status: {
                label: 'Estado',
                source: 'statuses'
            },
            from_date: { 
                label: 'Inicia',
                format: (val) => val ? val.replace('T', ' ') : '' 
            },
            to_date: { 
                label: 'Termina',
                format: (val) => val ? val.replace('T', ' ') : ''
            }
        }
    });
});