import AjaxDataTable from '../components/ajax-datatable/index';

document.addEventListener('DOMContentLoaded', () => {
    AjaxDataTable({
        tableId: 'categories-table',
        formId: 'categories-filters',
        debounce: 800,
        chips: {
            search: {
                label: 'Búsqueda'
            },
            is_active: {
                label: 'Estado',
                values: {
                    '1': 'Activo',
                    '0': 'Inactivo'
                }
            }
        }
    });
});
