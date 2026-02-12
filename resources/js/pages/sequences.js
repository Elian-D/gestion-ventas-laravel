import AjaxDataTable from '../components/ajax-datatable/index';

document.addEventListener('DOMContentLoaded', () => {
    AjaxDataTable({
        tableId: 'ncf-sequences-table',
        formId: 'ncf-sequences-filters',
        debounce: 500,
        chips: {
            // Filtro por Tipo de NCF (B01, B02, etc.)
            ncf_type_id: {
                label: 'Tipo de Comprobante',
                source: 'ncf_types' // Se alimentará de window.filterSources.ncf_types
            },
            // Filtro por Estado (Activo, Agotado, Vencido)
            status: {
                label: 'Estado',
                source: 'statuses' // Se alimentará de window.filterSources.statuses
            }
        }
    });
});