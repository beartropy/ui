export function btDialog({ globalSize = 'md', typeStyles = {} } = {}) {
    return {
        isOpen: false,
        type: 'info',
        title: '',
        description: '',
        icon: null,
        accept: null,
        reject: null,
        componentId: null,
        panelSizeClass: '',
        globalSize: globalSize,
        typeStyles: typeStyles,

        acceptBusy: false,
        rejectBusy: false,

        allowOutsideClick: false,
        allowEscape: false,

        get canCloseViaButton() {
            return true;
        },

        get isSingleButton() {
            const hasAcceptAction = this.accept && this.accept.method;
            const hasRejectAction = this.reject && this.reject.method;

            // If either has a method -> it's confirm/delete (2 buttons)
            if (hasAcceptAction || hasRejectAction) {
                return false;
            }

            // Otherwise, it's success/info/warning/error
            return true;
        },

        buttonColors: {
            info: 'bg-blue-700 hover:bg-blue-600 dark:bg-blue-800 dark:hover:bg-blue-700 text-white',
            success: 'bg-emerald-700 hover:bg-emerald-600 dark:bg-emerald-700 dark:hover:bg-emerald-600 text-white',
            warning: 'bg-amber-700 hover:bg-amber-600 dark:bg-amber-800 dark:hover:bg-amber-700 text-white',
            error: 'bg-rose-700 hover:bg-rose-600 dark:bg-rose-800 dark:hover:bg-rose-700 text-white',
            danger: 'bg-rose-700 hover:bg-rose-600 dark:bg-rose-800 dark:hover:bg-rose-700 text-white',
        },

        defaultIconForType(type) {
            switch (type) {
                case 'success': return 'check-circle';
                case 'error': return 'x-circle';
                case 'warning': return 'exclamation-triangle';
                case 'confirm': return 'question-mark-circle';
                case 'danger': return 'x-circle';
                case 'info':
                default: return 'information-circle';
            }
        },

        openDialog(raw) {
            // Livewire sends detail = [payload]
            const payload = Array.isArray(raw) ? (raw[0] ?? {}) : (raw ?? {});

            this.acceptBusy = false;
            this.rejectBusy = false;

            const size = payload.size ?? this.globalSize ?? 'md';

            const classes = {
                sm: 'max-w-md',
                md: 'max-w-lg',
                lg: 'max-w-xl',
                xl: 'max-w-2xl',
                '2xl': 'max-w-3xl',
            };

            this.panelSizeClass = classes[size] ?? classes['md'];

            this.type = payload.type ?? 'info';
            this.title = payload.title ?? '';
            this.description = payload.description ?? '';
            this.icon = payload.icon ?? this.defaultIconForType(this.type);
            this.accept = payload.accept ?? null;
            this.reject = payload.reject ?? null;
            this.componentId = payload.componentId ?? null;

            this.allowOutsideClick = payload.allowOutsideClick ?? false;
            this.allowEscape = payload.allowEscape ?? false;

            this.isOpen = true;

            this.$nextTick(() => {
                const primary = this.$el.querySelector('[x-on\\:click="clickAccept()"]');
                if (primary) primary.focus();
            });
        },

        close() {
            this.isOpen = false;
        },

        backdropClick() {
            if (this.allowOutsideClick) {
                this.close();
            }
        },

        clickAccept() {
            if (this.accept && this.accept.method && this.componentId && window.Livewire) {
                if (this.acceptBusy) return;

                this.acceptBusy = true;

                const params = Array.isArray(this.accept.params)
                    ? this.accept.params
                    : (this.accept.params !== undefined ? [this.accept.params] : []);

                const comp = window.Livewire.find(this.componentId);

                const finish = () => {
                    this.acceptBusy = false;
                    this.close();
                };

                if (comp) {
                    try {
                        const result = comp.call(this.accept.method, ...params);

                        if (result && typeof result.then === 'function') {
                            result.then(finish).catch(finish);
                        } else {
                            finish();
                        }
                    } catch (e) {
                        console.error('[Dialog] accept method error', e);
                        finish();
                    }
                } else {
                    finish();
                }
            } else {
                // No method: just close
                this.close();
            }
        },

        clickReject() {
            // If there's a method, handle the same as accept
            if (this.reject && this.reject.method && this.componentId && window.Livewire) {
                if (this.rejectBusy) return;

                this.rejectBusy = true;

                const params = Array.isArray(this.reject.params)
                    ? this.reject.params
                    : (this.reject.params !== undefined ? [this.reject.params] : []);

                const comp = window.Livewire.find(this.componentId);

                const finish = () => {
                    this.rejectBusy = false;
                    this.close();
                };

                if (comp) {
                    try {
                        const result = comp.call(this.reject.method, ...params);

                        if (result && typeof result.then === 'function') {
                            result.then(finish).catch(finish);
                        } else {
                            finish();
                        }
                    } catch (e) {
                        console.error('[Dialog] reject method error', e);
                        finish();
                    }
                } else {
                    finish();
                }
            } else {
                // No method: just close
                this.close();
            }
        },

        init() {
            window.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && this.isOpen && this.allowEscape) {
                    e.preventDefault();
                    this.close();
                }
            });
        }
    };
}
