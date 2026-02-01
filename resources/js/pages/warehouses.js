import AjaxDataTable from '../components/ajax-datatable/index';

document.addEventListener('DOMContentLoaded', () => {
    AjaxDataTable({
        tableId: 'warehouses-table',
        formId: 'warehouses-filters',
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
            },
            type: {
                label: 'Tipo',
                values: {
                    'static': 'Estático (Bodega/Fábrica)',
                    'mobile': 'Móvil (Camión/Ruta)',
                    'pos': 'Punto de Venta'
                }
            }
        }
    });
});