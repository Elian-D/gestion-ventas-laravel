import AjaxDataTable from '../components/ajax-datatable/index';

document.addEventListener('DOMContentLoaded', () => {
    AjaxDataTable({
        tableId: 'movements-table',
        formId: 'movements-filters',
        debounce: 800,
        chips: {
            search: { label: 'Búsqueda' },
            warehouse_id: {
                label: 'Almacén',
                source: 'warehouses'
            },
            product_id: {
                label: 'Producto',
                source: 'products'
            },
            type: {
                label: 'Operación',
                source: 'movementTypes'
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