import { applyFilters } from './filters';

export const getDeviceDefaultColumns = (container) => {
    const isMobile = window.innerWidth < 640;
    const desktopCols = JSON.parse(container.dataset.defaultDesktop || '[]');
    const mobileCols = JSON.parse(container.dataset.defaultMobile || '[]');
    return isMobile ? mobileCols : desktopCols;
};

export const resetColumns = (ctx) => {
    const container = document.getElementById('column-selector-container');
    if (!container) return;
    const target = getDeviceDefaultColumns(container);
    ctx.form.querySelectorAll('input[name="columns[]"]').forEach(cb => cb.checked = target.includes(cb.value));
    applyFilters(ctx);
};

export const checkInitialDeviceColumns = (ctx) => {
    const urlParams = new URLSearchParams(window.location.search);
    if (!urlParams.has('columns[]') && window.innerWidth < 640) {
        resetColumns(ctx);
    }
};