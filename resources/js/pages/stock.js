import AjaxDataTable from '../components/ajax-datatable/index';

document.addEventListener('DOMContentLoaded', () => {
    AjaxDataTable({
        tableId: 'stocks-table',
        formId: 'stocks-filters',
        debounce: 600, // Un poco más rápido para inventarios
        chips: {
            search: {
                label: 'Producto'
            },
            warehouse_id: {
                label: 'Ubicación',
                source: 'warehouses' 
            },
            category_id: {
                label: 'Categoría',
                source: 'categories'
            },
            status: {
                label: 'Estado de Stock',
                values: {
                    'ok': 'Suficiente',
                    'low': 'Stock Bajo',
                    'out': 'Agotado'
                }
            }
        }
    });
});