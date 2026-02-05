import { applyFilters, clearAllFilters } from './filters';
import { fetchTable } from './request';

export const setupEventListeners = (ctx) => {
    // Submit
    ctx.form.addEventListener('submit', (e) => {
        e.preventDefault();
        clearTimeout(ctx.state.timer);
        applyFilters(ctx);
    });

    // Inputs y Selects
    ctx.form.querySelectorAll('select, input[type="checkbox"], input[type="radio"], input[type="date"], input[type="datetime-local"]').forEach(el => 
        el.addEventListener('change', () => applyFilters(ctx))
    );

    ctx.form.querySelectorAll('input[type="text"], input[type="number"]').forEach(el => {
        el.addEventListener('input', () => {
            clearTimeout(ctx.state.timer);
            ctx.state.timer = setTimeout(() => applyFilters(ctx), ctx.config.debounce || 1000);
        });
    });

    // Paginación
    ctx.table.addEventListener('click', e => {
        const link = e.target.closest('.pagination a');
        if (link) {
            e.preventDefault();
            fetchTable(ctx, link.href);
            history.pushState({}, '', link.href);
        }
    });

    // Selección de filas
    ctx.table.addEventListener('change', (e) => {
        if (e.target.id === 'select-all-main') {
            const isChecked = e.target.checked;
            ctx.table.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = isChecked);
            ctx.updateSelectionState();
        } else if (e.target.classList.contains('row-checkbox')) {
            ctx.updateSelectionState();
        }
    });
};