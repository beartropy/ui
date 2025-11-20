// Dialog Module
export function dialog(payload) {
    if (typeof payload === 'string') {
        payload = {
            type: arguments[0],
            title: arguments[1] ?? '',
            description: arguments[2] ?? '',
        };
    }

    if (!payload.id) {
        payload.id =
            window.crypto?.randomUUID?.() ||
            'dialog-' + Math.random().toString(36).slice(2) + Date.now();
    }

    try {
        window.dispatchEvent(new CustomEvent('bt-dialog', { detail: payload }));
        return;
    } catch (e) {
        console.warn('[Beartropy] Dialog DOM dispatch fallback used:', e);
    }

    if (window.Livewire && window.Livewire.dispatch) {
        window.Livewire.dispatch('bt-dialog', payload);
        return;
    }

    console.warn('[Beartropy] Dialog: no Alpine ni Livewire disponibles');
}

// helpers tipo toast
['success', 'error', 'warning', 'info'].forEach(type => {
    dialog[type] = (title, description = '', options = {}) =>
        dialog({
            type,
            title,
            description,
            ...options,
        });
});

dialog.confirm = function (config) {
    dialog({
        type: 'confirm',
        icon: 'question-mark-circle',
        allowOutsideClick: false,
        allowEscape: false,
        ...config,
    });
};

dialog.delete = function (title, description = '', config = {}) {
    dialog({
        type: 'danger',
        icon: 'x-circle',
        title,
        description,
        allowOutsideClick: false,
        allowEscape: false,
        accept: {
            label: config.acceptLabel ?? 'Eliminar',
            method: config.method ?? null,
            params: config.params ?? [],
        },
        reject: {
            label: config.rejectLabel ?? 'Cancelar',
            method: config.rejectMethod ?? null,
            params: config.rejectParams ?? [],
        },
        size: config.size ?? null,
        componentId: config.componentId ?? null,
    });
};
