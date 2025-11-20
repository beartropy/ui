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

// Export all functions to window.$beartropy
window.$beartropy.dialog = dialog;
window.$beartropy.openModal = openModal;
window.$beartropy.closeModal = closeModal;
window.$beartropy.toast = toast;
window.$beartropy.beartropyTable = beartropyTable;
window.$beartropy.datetimepicker = datetimepicker;
window.$beartropy.timepicker = timepicker;
window.$beartropy.tagInput = tagInput;
window.$beartropy.confirmHost = confirmHost;

// Export i18n separately
window.beartropyI18n = beartropyI18n;
