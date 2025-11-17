@php

    $sizes = [
        'sm' => 'max-w-sm',
        'md' => 'max-w-md',
        'lg' => 'max-w-lg',
        'xl' => 'max-w-xl',
        '2xl' => 'max-w-2xl',
    ];

    $typeStyles = [
        'info' => [
            'iconBg'   => 'bg-blue-100 dark:bg-blue-900/40',
            'iconText' => 'text-blue-600 dark:text-blue-300',
        ],
        'success' => [
            'iconBg'   => 'bg-emerald-100 dark:bg-emerald-900/40',
            'iconText' => 'text-emerald-600 dark:text-emerald-300',
        ],
        'warning' => [
            'iconBg'   => 'bg-amber-100 dark:bg-amber-900/40',
            'iconText' => 'text-amber-600 dark:text-amber-300',
        ],
        'error' => [
            'iconBg'   => 'bg-rose-100 dark:bg-rose-900/40',
            'iconText' => 'text-rose-600 dark:text-rose-300',
        ],
        'confirm' => [
            'iconBg'   => 'bg-indigo-100 dark:bg-indigo-900/40',
            'iconText' => 'text-indigo-600 dark:text-indigo-300',
        ],
        'danger' => [
            'iconBg'   => 'bg-red-100 dark:bg-red-900/40',
            'iconText' => 'text-red-600 dark:text-red-300',
        ],
    ];
@endphp

<div
    x-data="btDialog()"
    x-on:bt-dialog.window="openDialog($event.detail)"
    x-show="open"
    x-cloak
    class="fixed inset-0 z-[9999] flex justify-center items-start"
    aria-modal="true"
    role="dialog"
>
    {{-- Backdrop --}}
    <div
        class="fixed inset-0 bg-black/40 backdrop-blur-sm"
        @click="backdropClick()"
    ></div>

    {{-- Wrapper para 1/3 top --}}
    <div class="relative w-full flex justify-center pt-[15vh] md:pt-[18vh] px-4">
        {{-- Panel --}}
        <div
            x-trap.noscroll.inert="open"
            class="relative w-full rounded-2xl shadow-2xl border border-slate-200/60 dark:border-slate-800/80
                bg-white dark:bg-slate-900/95 overflow-hidden
                transition-transform duration-200 ease-out transform"
                :class="panelSizeClass"

            x-transition
        >
            {{-- Botón cerrar flotante --}}
            <button
                type="button"
                x-show="canCloseViaButton"
                @click="close()"
                class="absolute top-3 right-3 z-10
                       inline-flex items-center justify-center rounded-full
                       border-slate-200/70 dark:border-slate-700/80
                       bg-white/85 dark:bg-slate-900/85
                       backdrop-blur-sm shadow-sm
                       text-slate-500 hover:text-slate-800 dark:text-slate-300 dark:hover:text-slate-100
                       hover:bg-white dark:hover:bg-slate-800
                       focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2
                       focus-visible:ring-slate-500/70 focus-visible:ring-offset-slate-900/60
                       transition
                       p-2 md:p-1.5"
            >
                <span class="sr-only">Close</span>
                <svg class="h-4 w-4 md:h-5 md:w-5" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" fill="none">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            {{-- Header + contenido --}}
            <div class="px-4 pt-2 pb-2">
                {{-- Layout 2 columnas: icono | título + descripción --}}
                <div class="mt-2 flex items-start gap-4">
                    {{-- Icono izquierda --}}
                    <div
                        class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full"
                        x-show="icon"
                        :class="typeStyles[type]?.iconBg"
                    >
                        <svg class="h-9 w-9" fill="none" viewBox="0 0 24 24"
                             stroke-width="1.8"
                             :class="typeStyles[type]?.iconText"
                             stroke="currentColor">
                            {{-- check-circle --}}
                            <path
                                x-show="icon === 'check-circle'"
                                stroke-linecap="round" stroke-linejoin="round"
                                d="M5 13l4 4L19 7"
                            />
                            {{-- x-circle --}}
                            <path
                                x-show="icon === 'x-circle'"
                                stroke-linecap="round" stroke-linejoin="round"
                                d="M18 6 6 18M6 6l12 12"
                            />
                            {{-- exclamation-triangle --}}
                            <path
                                x-show="icon === 'exclamation-triangle'"
                                stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a1 1 0 00.86 1.5h18.64a1 1 0 00.86-1.5L13.71 3.86a1 1 0 00-1.72 0z"
                            />
                            {{-- information-circle --}}
                            <path
                                x-show="icon === 'information-circle'"
                                stroke-linecap="round" stroke-linejoin="round"
                                d="M13 16h-1v-4h-1m1-4h.01M12 3a9 9 0 100 18 9 9 0 000-18z"
                            />
                            {{-- question-mark-circle / question --}}
                            <path
                                x-show="icon === 'question-mark-circle' || icon === 'question'"
                                stroke-linecap="round" stroke-linejoin="round"
                                d="M8 10a4 4 0 118 0c0 1.657-1.343 3-3 3v1m0 2h.01M12 3a9 9 0 100 18 9 9 0 000-18z"
                            />
                        </svg>
                    </div>

                    {{-- Título + descripción derecha --}}
                    <div class="flex-1 min-w-0 text-left">
                        <h2
                            class="text-lg font-semibold text-slate-900 dark:text-slate-50"
                            x-text="title"
                        ></h2>

                        <p
                            x-show="description"
                            class="mt-1 text-base text-slate-600 dark:text-slate-300 whitespace-pre-line"
                            x-text="description"
                        ></p>
                    </div>
                </div>
            </div>

            {{-- Footer / botones --}}
            <div class="px-4 py-2 mb-2 mt-1 bg-slate-50/90 dark:bg-slate-900/80 flex flex-wrap justify-end gap-2">
                {{-- Botón REJECT (Cancelar) --}}
                <template x-if="reject">
                    <button
                        type="button"
                        class="inline-flex items-center justify-center rounded-lg border border-slate-300
                            px-4 py-1.5 text-sm font-medium text-slate-700 bg-white
                            hover:bg-slate-50 dark:bg-slate-900 dark:text-slate-200 dark:border-slate-600
                            dark:hover:bg-slate-800"
                        @click="clickReject()"
                        x-text="reject.label ?? 'Cancelar'"
                    ></button>
                </template>

                {{-- Botón ACCEPT (Confirmar / Eliminar) --}}
                <template x-if="accept && accept.method">
                    <button
                        type="button"
                        class="inline-flex items-center justify-center rounded-lg px-4 py-1.5 text-sm font-medium
                            text-white"
                        :class="type === 'danger'
                            ? 'bg-rose-700 hover:bg-rose-600 dark:bg-rose-800 dark:hover:bg-rose-700 '
                            : 'bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600'"
                        @click="clickAccept()"
                        x-text="accept.label ?? 'OK'"
                    ></button>
                </template>

                {{-- Botón único (success/info/warning/error) --}}
                <template x-if="isSingleButton">
                    <button
                        type="button"
                        class="rounded-lg px-4 py-2.5 text-sm font-semibold tracking-wide transition
                            w-full text-center"
                        :class="buttonColors[type] ?? 'bg-slate-700 dark:bg-slate-600 text-white'"
                        @click="close()"
                    >
                        OK
                    </button>
                </template>

            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('btDialog', () => ({
            open: false,
            type: 'info',
            title: '',
            description: '',
            icon: null,
            accept: null,
            reject: null,
            componentId: null,
            panelSizeClass: '',
            globalSize: @js($size),

            allowOutsideClick: false,
            allowEscape: false,

            typeStyles: @js($typeStyles),

            get canCloseViaButton() {
                return true;
            },

            get isSingleButton() {
                const hasAcceptAction = this.accept && this.accept.method;
                const hasRejectAction = this.reject && this.reject.method;

                // Si alguno tiene método -> es confirm/delete (2 botones)
                if (hasAcceptAction || hasRejectAction) {
                    return false;
                }

                // De lo contrario, es success/info/warning/error
                return true;
            },

            buttonColors: {
                info:    'bg-blue-700 hover:bg-blue-600 dark:bg-blue-800 dark:hover:bg-blue-700 text-white',
                success: 'bg-emerald-700 hover:bg-emerald-600 dark:bg-emerald-700 dark:hover:bg-emerald-600 text-white',
                warning: 'bg-amber-700 hover:bg-amber-600 dark:bg-amber-800 dark:hover:bg-amber-700 text-white',
                error:   'bg-rose-700 hover:bg-rose-600 dark:bg-rose-800 dark:hover:bg-rose-700 text-white',
                danger:  'bg-red-700 hover:bg-red-600 dark:bg-red-800 dark:hover:bg-red-700 text-white',
            },

            defaultIconForType(type) {
                switch (type) {
                    case 'success': return 'check-circle';
                    case 'error':   return 'x-circle';
                    case 'warning': return 'exclamation-triangle';
                    case 'confirm': return 'question-mark-circle';
                    case 'danger':  return 'x-circle';
                    case 'info':
                    default:        return 'information-circle';
                }
            },

            openDialog(raw) {
                // Livewire manda detail = [payload]
                const payload = Array.isArray(raw) ? (raw[0] ?? {}) : (raw ?? {});

                const size = payload.size ?? this.globalSize ?? 'md';

                const classes = {
                    sm:  'max-w-md',
                    md:  'max-w-lg',
                    lg:  'max-w-xl',
                    xl:  'max-w-2xl',
                    '2xl':'max-w-3xl',
                };


                this.panelSizeClass = classes[size] ?? classes['md'];

                this.type        = payload.type ?? 'info';
                this.title       = payload.title ?? '';
                this.description = payload.description ?? '';
                this.icon        = payload.icon ?? this.defaultIconForType(this.type);
                this.accept      = payload.accept ?? null;
                this.reject      = payload.reject ?? null;
                this.componentId = payload.componentId ?? null;

                this.allowOutsideClick = payload.allowOutsideClick ?? false;
                this.allowEscape       = payload.allowEscape ?? false;

                this.open = true;

                this.$nextTick(() => {
                    const primary = this.$el.querySelector('[x-on\\:click="clickAccept()"]');
                    if (primary) primary.focus();
                });
            },

            close() {
                this.open = false;
            },

            backdropClick() {
                if (this.allowOutsideClick) {
                    this.close();
                }
            },

            clickAccept() {
                if (this.accept && this.accept.method && this.componentId && window.Livewire) {
                    const params = Array.isArray(this.accept.params)
                        ? this.accept.params
                        : (this.accept.params !== undefined ? [this.accept.params] : []);

                    const comp = window.Livewire.find(this.componentId);
                    if (comp) {
                        comp.call(this.accept.method, ...params);
                    }
                }
                this.close();
            },

            clickReject() {
                if (this.reject && this.reject.method && this.componentId && window.Livewire) {
                    const params = Array.isArray(this.reject.params)
                        ? this.reject.params
                        : (this.reject.params !== undefined ? [this.reject.params] : []);

                    const comp = window.Livewire.find(this.componentId);
                    if (comp) {
                        comp.call(this.reject.method, ...params);
                    }
                }
                this.close();
            },

            init() {
                window.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape' && this.open && this.allowEscape) {
                        e.preventDefault();
                        this.close();
                    }
                });
            },
        }));
    });
</script>
