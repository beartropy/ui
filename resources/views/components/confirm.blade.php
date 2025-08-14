@php $id = $id ?? 'beartropy-confirm'; @endphp

<div
  wire:ignore
  x-data="$beartropy.confirmHost({
    id: '{{ $id }}',
    defaultPlacement: 'top',
    defaultPanelClass: 'mt-32'
  })"
  x-on:bt-confirm.window="handle($event)"
  x-on:bt-confirm.document="handle($event)"
>
  <template x-teleport="body">
    <div x-show="open || anim === 'leave'" x-cloak @keydown.window="onKeydown($event)" class="fixed inset-0 z-[1000]">
      <!-- Backdrop -->
        <div
        class="absolute inset-0 bg-black will-change-[opacity] select-none z-[1001]"
        :class="overlayBlur ? 'backdrop-blur-sm' : ''"
        :style="overlayStyle()"
        @click="onBackdrop($event)"
        aria-hidden="true"
        ></div>

      <!-- Dialog container -->
      <div class="fixed inset-0 p-4 z-[1002] pointer-events-none" :class="containerClass()">
        <div
          role="dialog"
          aria-modal="true"
          :aria-labelledby="'confirm-title-' + id"
          :aria-describedby="'confirm-desc-' + id"
          class="relative w-full rounded-xl bg-white text-gray-900 shadow-xl dark:bg-zinc-900 dark:text-white will-change-[transform,opacity] pointer-events-auto"
          :class="sizeClasses() + ' ' + dialogMotionClass() + ' ' + panelClass"
          :style="dialogStyle()"
        >
          <div class="flex items-center justify-between gap-3 p-4 border-b border-black/10 dark:border-white/10">
            <h3 class="text-lg font-semibold truncate" :id="'confirm-title-' + id" x-text="cfg.title || @js($title ?? '')"></h3>
            <button type="button" class="p-2 rounded hover:bg-black/5 dark:hover:bg-white/10" @click="close()" aria-label="Close">✕</button>
          </div>

          <div class="p-4">
            <p x-show="cfg.message" x-cloak :id="'confirm-desc-' + id" class="mb-2" x-text="cfg.message"></p>
            <div x-show="!cfg.message" x-cloak>
              {{ $slot ?? '' }}
            </div>
          </div>

          <div class="flex items-center justify-end gap-2 p-4 border-t border-black/10 dark:border-white/10">
            <template x-for="(btn,i) in buttons" :key="i">
              @if (view()->exists('components.button'))
                <x-button raw
                  :disabled="true" x-bind:disabled="btnLoading[i]"
                  @click="run(btn,i)"
                  x-bind:class="btnClass(btn)"
                  x-ref="first"
                >
                  <span x-text="btn.label ?? 'OK'"></span>
                </x-button>
              @else
                <button
                  type="button"
                  class="inline-flex items-center gap-2 rounded-xl px-3 py-2 text-sm transition
                         focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-60 disabled:cursor-not-allowed"
                  :class="btnClass(btn)"
                  :disabled="btnLoading[i]"
                  @click="run(btn,i)"
                  x-ref="first"
                >
                  <span x-text="btn.label ?? 'OK'"></span>
                </button>
              @endif
            </template>
          </div>
        </div>
      </div>
    </div>
  </template>
</div>
