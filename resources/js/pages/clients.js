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
            has_debt: {
                label: 'Saldo',
                values: {
                    'yes': 'Con Saldo Pendiente',
                    'no': 'Sin Deuda'
                }
            },
            over_limit: {
                label: 'Crédito',
                values: {
                    '1': 'Límite Excedido'
                }
            },
            state: {
                label: 'Estado',
                source: 'state'
            },
            type: {
                label: 'Tipo de Cliente',
                values: {
                    'individual': 'Individual',
                    'company': 'Compañía'
                }
            },
            tax_type: {
                label: 'Indentificador Fiscal',
                source: 'tax_type'
            },
            from_date: {
                label: 'Creado desde',
            },
            to_date: {
                label: 'Creado hasta',
            },
            search: {
                label: 'Búsqueda'
            }
        }
    });

});
