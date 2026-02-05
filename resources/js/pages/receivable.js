// resources/js/pages/accounting/receivable.js
import AjaxDataTable from '../components/ajax-datatable/index';

document.addEventListener('DOMContentLoaded', () => {
    AjaxDataTable({
        tableId: 'receivables-table',
        formId: 'receivables-filters',
        debounce: 800,
        chips: {
            client_id: {
                label: 'Cliente',
                source: 'clients' // Se cargará de window.filterSources.clients
            },
            status: {
                label: 'Estado',
                source: 'statuses'
            },
            overdue: {
                label: 'Vencimiento',
                values: {
                    'yes': 'Facturas Vencidas',
                    'no': 'Al Día'
                }
            },
            min_balance: {
                label: 'Saldo mín.'
            },
            max_balance: {
                label: 'Saldo máx.'
            },
            search: {
                label: 'Búsqueda'
            }
        }
    });
});