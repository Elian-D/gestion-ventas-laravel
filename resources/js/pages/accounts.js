import AjaxDataTable from '../components/ajax-datatable/index';

document.addEventListener('DOMContentLoaded', () => {
    AjaxDataTable({
        tableId: 'accounts-table',
        formId: 'accounts-filters',
        debounce: 800,
    });
});
