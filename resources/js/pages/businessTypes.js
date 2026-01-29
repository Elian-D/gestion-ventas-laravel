import AjaxDataTable from '../components/ajax-datatable/index';

document.addEventListener('DOMContentLoaded', () => {
    AjaxDataTable({
        tableId: 'businessTypes-table',
        formId: 'businessTypes-filters',
        debounce: 800,
        chips: {
            search: {
                label: 'Búsqueda'
            },
            activo: {
                label: 'Estado',
                values: {
                    '1': 'Activo',
                    '0': 'Inactivo'
                }
            }
        }
    });
});
