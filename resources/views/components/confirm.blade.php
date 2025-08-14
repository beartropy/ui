@php
    [$colorPreset, $sizePreset] = $getComponentPresets('modal');
    $id = $id ?? 'beartropy-confirm';
@endphp

<x-modal
    :id="$id"
    :styled="($styled ?? true)"
    :size="($size ?? 'md')"
    :close-on-backdrop="($closeOnBackdrop ?? true)"
    :close-on-escape="($closeOnEscape ?? true)"
    x-data="$beartropy.confirmHost({ id: '{{ $id }}' })"
    x-on:bt-confirm.window="handle($event)"
>
    <x-slot:name>
        <span x-text="cfg.title ?? @js($title ?? '')"></span>
    </x-slot:name>

    <div class="flex flex-col gap-3">
        <template x-if="cfg.message">
            <p x-text="cfg.message"></p>
        </template>

        <template x-if="!cfg.message">
            {{ $slot ?? '' }}
        </template>
    </div>

    <x-slot:footer>
        <div class="flex items-center justify-end gap-2">
            <template x-for="(btn, i) in buttons" :key="i">
                <button type="button"
                    class="inline-flex items-center gap-2 rounded-xl px-3 py-2 text-sm transition
                           focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-60 disabled:cursor-not-allowed"
                    :class="variantClass(btn.variant)"
                    @click="run(btn, i)"
                    :disabled="btnLoading[i]"
                >
                    <svg x-show="btnLoading[i]" class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none">
                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" opacity="0.25"></circle>
                        <path d="M22 12a10 10 0 0 1-10 10" stroke="currentColor" stroke-width="4" stroke-linecap="round"></path>
                    </svg>
                    <span x-text="btn.label ?? 'OK'"></span>
                </button>
            </template>
        </div>
    </x-slot:footer>
</x-modal>

@push('scripts')
<script>
window.$beartropy = window.$beartropy || {};
window.$beartropy.confirmHost = function ({ id }) {
  return {
    id,
    cfg: {},
    buttons: [],
    btnLoading: [],

    handle(ev) {
      const d = ev.detail || {};
      if (!d.target || d.target !== this.id) return;

      this.cfg = d;
      this.buttons = Array.isArray(d.buttons) && d.buttons.length
        ? d.buttons
        : [{ label: 'OK', mode: 'close', variant: 'soft' }];

      this.btnLoading = this.buttons.map(() => false);

      // Abrir modal
      $beartropy.openModal(this.id);
    },

    variantClass(variant) {
      // Mapeo rápido a tus presets (ajustá a tus clases reales)
      switch (variant) {
        case 'danger':  return 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-400 dark:focus:ring-red-600';
        case 'primary': return 'bg-beartropy-600 text-white hover:bg-beartropy-700 focus:ring-beartropy-400 dark:focus:ring-beartropy-600';
        case 'outline': return 'bg-transparent border border-gray-400/60 text-gray-800 dark:text-gray-200 hover:bg-gray-100/40 focus:ring-gray-300';
        case 'ghost':   return 'bg-transparent text-gray-700 dark:text-gray-200 hover:bg-gray-100/40 focus:ring-gray-300';
        default:        return 'bg-gray-200 text-gray-900 hover:bg-gray-300 focus:ring-gray-300 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600';
      }
    },

    async run(btn, i) {
      const mode = btn.mode || (btn.wire ? 'wire' : (btn.emit ? 'emit' : 'close'));
      const dismiss = !!btn.dismissAfter;

      if (mode === 'wire' && this.cfg.componentId && btn.wire) {
        try {
          this.btnLoading[i] = true;
          const comp = Livewire.find(this.cfg.componentId);
          await comp.call(btn.wire, ...(btn.params || []));
        } finally {
          this.btnLoading[i] = false;
          if (dismiss) this.close();
        }
        return;
      }

      if (mode === 'emit' && btn.emit) {
        // Emite a Livewire; si hay componentId, se lo dirigimos
        if (this.cfg.componentId) {
          Livewire.dispatch(btn.emit, btn.payload || {}, { to: this.cfg.componentId });
        } else {
          Livewire.dispatch(btn.emit, btn.payload || {});
        }
        if (dismiss) this.close();
        return;
      }

      // close
      this.close();
    },

    close() {
      $beartropy.closeModal(this.id);
    },
  }
};
</script>
@endpush
