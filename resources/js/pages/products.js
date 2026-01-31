import AjaxDataTable from '../components/ajax-datatable/index';

document.addEventListener('DOMContentLoaded', () => {
    AjaxDataTable({
        tableId: 'products-table',
        formId: 'products-filters',
        debounce: 800,
        chips: {
            search: {
                label: 'Búsqueda'
            },
            categories: {
                label: 'Categoría',
                source: 'categories' // Bebe de window.filterSources.categories
            },
            units: {
                label: 'Unidad',
                source: 'units'
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