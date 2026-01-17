import { buildUrl, fetchTable } from './request';
import { renderChips } from './chips';

export const getParams = (form) => {
    const formData = new FormData(form);
    const params = {};
    for (const [key, value] of formData.entries()) {
        if (value === '') continue;
        if (key.endsWith('[]')) {
            if (!params[key]) params[key] = [];
            params[key].push(value);
        } else {
            params[key] = value;
        }
    }
    return params;
};

export const applyFilters = (ctx) => {
    const params = getParams(ctx.form);
    const url = buildUrl(ctx.form);
    fetchTable(ctx, url);
    history.pushState({}, '', url);
    renderChips(ctx, params);
};

export const clearAllFilters = (ctx) => {
    const currentPerPage = ctx.form.querySelector('[name="per_page"]')?.value || 10;
    const selectedCols = Array.from(ctx.form.querySelectorAll('input[name="columns[]"]:checked')).map(cb => cb.value);

    ctx.form.querySelectorAll('input[type="text"], input[type="search"]').forEach(i => i.value = '');
    ctx.form.querySelectorAll('select').forEach(s => s.selectedIndex = 0);

    if (ctx.form.querySelector('[name="per_page"]')) ctx.form.querySelector('[name="per_page"]').value = currentPerPage;
    ctx.form.querySelectorAll('input[name="columns[]"]').forEach(cb => cb.checked = selectedCols.includes(cb.value));

    applyFilters(ctx);
};