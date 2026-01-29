import AjaxDataTable from '../components/ajax-datatable/index';

document.addEventListener('DOMContentLoaded', () => {
    AjaxDataTable({
        tableId: 'pos-table',
        formId: 'pos-filters',
        debounce: 800,
        chips: {
            search: {
                label: 'Búsqueda'
            },
            client: {
                label: 'Cliente',
                source: 'clients' // Referencia a window.filterSources.clients
            },
            business_type: {
                label: 'Tipo de Negocio',
                source: 'businessTypes'
            },
            state: {
                label: 'Provincia',
                source: 'states'
            },
            active: {
                label: 'Estado Operativo',
                values: {
                    '1': 'Activo',
                    '0': 'Inactivo'
                }
            }
        }
    });
});