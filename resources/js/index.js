// Beartropy UI - Main Entry Point
import { dialog } from './modules/dialog.js';
import { openModal, closeModal } from './modules/modal.js';
import { toast } from './modules/toast.js';
import { beartropyTable } from './modules/table.js';
import { beartropyI18n, datetimepicker } from './modules/datetime-picker.js';
import { timepicker } from './modules/time-picker.js';
import { tagInput } from './modules/tag-input.js';
import { confirmHost } from './modules/confirm.js';

// Initialize global namespace
window.$beartropy = window.$beartropy || {};

// Export global tools immediately (they don't depend on Alpine init)
window.$beartropy.dialog = dialog;
window.$beartropy.openModal = openModal;
window.$beartropy.closeModal = closeModal;
window.$beartropy.toast = toast;
// Export i18n separately
window.beartropyI18n = beartropyI18n;

document.addEventListener('alpine:init', () => {
    // Register Alpine components
    Alpine.data('beartropyTable', beartropyTable);
    Alpine.data('datetimepicker', datetimepicker);
    Alpine.data('timepicker', timepicker);
    Alpine.data('tagInput', tagInput);
    Alpine.data('confirmHost', confirmHost);

    // Keep global references for legacy/external usage if needed
    window.$beartropy.beartropyTable = beartropyTable;
    window.$beartropy.datetimepicker = datetimepicker;
    window.$beartropy.timepicker = timepicker;
    window.$beartropy.tagInput = tagInput;
    window.$beartropy.confirmHost = confirmHost;
});
