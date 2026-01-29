import AjaxDataTable from '../components/ajax-datatable/index';

document.addEventListener('DOMContentLoaded', () => {
    AjaxDataTable({
        tableId: 'equipments-table',
        formId: 'equipments-filters',
        debounce: 800,
        chips: {
            search: {
                label: 'Búsqueda'
            },
            equipment_type_id: {
                label: 'Tipo de Equipo',
                source: 'equipmentTypes'
            },
            point_of_sale_id: {
                label: 'Punto de Venta',
                source: 'pointsOfSale'
            },
            active: {
                label: 'Estado',
                values: {
                    '1': 'Activo',
                    '0': 'Inactivo'
                }
            }
        }
    });
});
