// Toast Module
export function toast(type, title, message = '', duration = 4000, position = 'top-right') {
    const toastObj = {
        id: (window.crypto && window.crypto.randomUUID)
            ? window.crypto.randomUUID()
            : 'toast-' + Math.random().toString(36).slice(2) + Date.now(),
        type,
        title,
        message,
        duration,
        position,
    };

    // 🔥 1. Intentar usar el store de Alpine (inmediato)
    if (window.Alpine && Alpine.store('toasts')) {
        Alpine.store('toasts').add(toastObj);
    }
    // ⚡ 2. Si no existe, usar Livewire (menos probable, pero más universal)
    else if (window.Livewire && window.Livewire.dispatch) {
        window.Livewire.dispatch('beartropy-add-toast', toastObj);
    }
    // 3. Fallback: warning
    else {
        console.warn('[Beartropy] Toast: no Alpine store ni Livewire.dispatch disponible');
    }
}

['success', 'error', 'warning', 'info'].forEach(type => {
    toast[type] = (title, message, duration, position) =>
        toast(type, title, message, duration, position);
});
