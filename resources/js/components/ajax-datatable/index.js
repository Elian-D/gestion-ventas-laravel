import { createInitialState } from './state';
import { setupEventListeners } from './events';
import { applyFilters, getParams } from './filters';
import { renderChips } from './chips';
import { checkInitialDeviceColumns, resetColumns } from './columns';

export default function AjaxDataTable(config) {
    const table = document.getElementById(config.tableId);
    const form = document.getElementById(config.formId);
    if (!table || !form) return;

    const ctx = {
        table,
        form,
        chipsContainer: document.getElementById('active-filters'),
        config,
        state: createInitialState(),
        
        // Métodos de utilidad que necesitan acceso al contexto
        updateSelectionState: function() {
            const allCheckboxes = Array.from(this.table.querySelectorAll('.row-checkbox'));
            const master = document.getElementById('select-all-main');
            
            allCheckboxes.forEach(cb => {
                const id = cb.value;
                const index = this.state.selectedIds.indexOf(id);
                if (cb.checked && index === -1) this.state.selectedIds.push(id);
                else if (!cb.checked && index !== -1) this.state.selectedIds.splice(index, 1);
            });

            if (master) {
                const checked = allCheckboxes.filter(cb => cb.checked).length;
                master.checked = allCheckboxes.length > 0 && checked === allCheckboxes.length;
                master.indeterminate = checked > 0 && checked < allCheckboxes.length;
            }
            document.dispatchEvent(new CustomEvent('table-selection-changed', { detail: { ids: [...this.state.selectedIds] } }));
        },

        syncCheckboxes: function() {
            this.table.querySelectorAll('.row-checkbox').forEach(cb => {
                cb.checked = this.state.selectedIds.includes(cb.value);
            });
            this.updateSelectionState();
        }
    };

    // Exponer funciones globales requeridas
    window.resetTableColumns = () => resetColumns(ctx);
    window.clearTableSelection = () => {
        ctx.state.selectedIds = [];
        ctx.table.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = false);
        ctx.updateSelectionState();
    };

    setupEventListeners(ctx);
    checkInitialDeviceColumns(ctx);
    renderChips(ctx, getParams(ctx.form));
}