// Toast Module
export function toast(type, title, message = '', duration = 4000, position = 'top-right', action = null, actionUrl = null) {
    const toastObj = {
        id: (window.crypto && window.crypto.randomUUID)
            ? window.crypto.randomUUID()
            : 'toast-' + Math.random().toString(36).slice(2) + Date.now(),
        type,
        title,
        message,
        duration,
        position,
        action,
        actionUrl,
    };

    // 1. Try Alpine store first (immediate)
    if (window.Alpine && Alpine.store('toasts')) {
        Alpine.store('toasts').add(toastObj);
    }
    // 2. Fall back to Livewire dispatch
    else if (window.Livewire && window.Livewire.dispatch) {
        window.Livewire.dispatch('beartropy-add-toast', toastObj);
    }
    // 3. Fallback: warning
    else {
        console.warn('[Beartropy] Toast: no Alpine store or Livewire.dispatch available');
    }
}

['success', 'error', 'warning', 'info'].forEach(type => {
    toast[type] = (title, message, duration, position, action, actionUrl) =>
        toast(type, title, message, duration, position, action, actionUrl);
});
