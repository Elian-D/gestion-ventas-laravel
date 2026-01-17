import AjaxDataTable from '../components/ajax-datatable/index';

document.addEventListener('DOMContentLoaded', () => {
    AjaxDataTable({
        tableId: 'clients-table',
        formId: 'clients-filters',
        chips: {
            active: {
                label: 'Estado Operativo',
                values: {
                    '1': 'Activo',
                    '0': 'Inactivo'
                }
            },
            estado_cliente: {
                label: 'Estado del Cliente',
                source: 'estadosClientes'
            },
            business_type: {
                label: 'Tipo de Negocio',
                source: 'tiposNegocio'
            },
            search: {
                label: 'Búsqueda'
            }
        }
    });

});
