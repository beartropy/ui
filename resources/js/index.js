// Beartropy UI - Main Entry Point
import { dialog } from './modules/dialog.js';
import { openModal, closeModal } from './modules/modal.js';
import { toast } from './modules/toast.js';
import { beartropyTable } from './modules/table.js';
import { beartropyDatetimepicker } from './modules/datetime-picker.js';
import { beartropyTimepicker } from './modules/time-picker.js';
import { beartropyTagInput } from './modules/tag-input.js';
import { confirmHost } from './modules/confirm.js';
import { btDialog } from './modules/bt-dialog.js';
import { beartropySelect } from './modules/select.js';
import { beartropyFileDropzone } from './modules/file-dropzone.js';
import { beartropyChatInput } from './modules/chat-input.js';
import { beartropyLookup } from './modules/lookup.js';
import { initTheme, btToggleTheme } from './modules/toggle-theme.js';

// Initialize global namespace
window.$beartropy = window.$beartropy || {};

// Export global tools immediately (they don't depend on Alpine init)
window.$beartropy.dialog = dialog;
window.$beartropy.openModal = openModal;
window.$beartropy.closeModal = closeModal;
window.$beartropy.toast = toast;

// Theme: apply before CSS/Alpine to prevent FOUC
initTheme();

document.addEventListener('alpine:init', () => {
    // Register Alpine components
    Alpine.data('beartropyTable', beartropyTable);
    Alpine.data('beartropyDatetimepicker', beartropyDatetimepicker);
    Alpine.data('beartropyTimepicker', beartropyTimepicker);
    Alpine.data('beartropyTagInput', beartropyTagInput);
    Alpine.data('confirmHost', confirmHost);
    Alpine.data('btDialog', btDialog);
    Alpine.data('beartropySelect', beartropySelect);
    Alpine.data('beartropyFileDropzone', beartropyFileDropzone);
    Alpine.data('beartropyChatInput', beartropyChatInput);
    Alpine.data('beartropyLookup', beartropyLookup);
    Alpine.data('btToggleTheme', btToggleTheme);

    // Keep global references for legacy/external usage if needed
    window.$beartropy.beartropyTable = beartropyTable;
    window.$beartropy.beartropyDatetimepicker = beartropyDatetimepicker;
    window.$beartropy.beartropyTimepicker = beartropyTimepicker;
    window.$beartropy.beartropyTagInput = beartropyTagInput;
    window.$beartropy.confirmHost = confirmHost;
    window.$beartropy.btDialog = btDialog;
    window.$beartropy.beartropySelect = beartropySelect;
    window.$beartropy.beartropyFileDropzone = beartropyFileDropzone;
    window.$beartropy.beartropyChatInput = beartropyChatInput;
    window.$beartropy.beartropyLookup = beartropyLookup;
    window.$beartropy.btToggleTheme = btToggleTheme;
});
