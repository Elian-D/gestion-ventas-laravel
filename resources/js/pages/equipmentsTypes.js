import AjaxDataTable from '../components/ajax-datatable/index';

document.addEventListener('DOMContentLoaded', () => {
    AjaxDataTable({
        tableId: 'equipmentsTypes-table',
        formId: 'equipmentsTypes-filters',
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
