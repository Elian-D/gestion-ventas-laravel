import AjaxDataTable from '../components/ajax-datatable/index';

document.addEventListener('DOMContentLoaded', () => {
    AjaxDataTable({
        tableId: 'units-table',
        formId: 'units-filters',
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
