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
          class="relative p-2 w-full rounded-xl bg-white text-gray-900 shadow-xl dark:bg-zinc-900 dark:text-white will-change-[transform,opacity] pointer-events-auto"
          :class="sizeClasses() + ' ' + dialogMotionClass() + ' ' + panelClass"
          :style="dialogStyle()"
        >

            <div class="flex items-center">
                <template x-if="cfg.icon">
                    <div class="pt-4 px-4">

                        {{-- Íconos predefinidos --}}
                        <template x-if="cfg.icon === 'success'">
                            <x-beartropy-ui::icon name="check-circle" class="shrink-0 text-green-600 dark:text-green-400 !w-16 !h-16" />
                        </template>

                        <template x-if="cfg.icon === 'info'">
                        <x-beartropy-ui::icon name="exclamation-circle" class="shrink-0 text-blue-600 dark:text-blue-400 !w-16 !h-16" />
                        </template>

                        <template x-if="cfg.icon === 'danger'">
                        <x-beartropy-ui::icon name="exclamation-triangle" class="shrink-0 text-red-600 dark:text-red-400 !w-16 !h-16" />
                        </template>

                        <template x-if="cfg.icon === 'warning'">
                        <x-beartropy-ui::icon name="exclamation-triangle" class="shrink-0 text-amber-600 dark:text-amber-400 !w-16 !h-16" />
                        </template>

                    </div>
                </template>
                <div class="flex-1 min-w-0 mt-0.5 pt-4 pr-4 pb-2">
                    <h3 class="text-lg pl-2 font-semibold truncate" :id="'confirm-title-' + id" x-html="cfg.title || @js($title ?? '')"></h3>
                    <div class="p-2">
                        <p x-show="cfg.message" x-cloak :id="'confirm-desc-' + id" class="mb-2" x-html="cfg.message"></p>
                        <div x-show="!cfg.message" x-cloak>
                            {{ $slot ?? '' }}
                        </div>
                    </div>
                </div>
            </div>
            <button type="button"
                class="absolute top-3 right-3 text-gray-500 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-100 transition"
                @click="close()"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M6 18L18 6M6 6l12 12" />
                    </svg>
            </button>

          <div class="flex items-center justify-end gap-2 p-2 border-black/10 dark:border-white/10">
            <template x-for="(btn,i) in buttons" :key="i">
                <button
                  type="button"
                  class="inline-flex w-full items-center justify-center rounded-md border transition-colors relative
            text-base h-10 px-3 py-2
             border-none
             disabled:opacity-60 disabled:cursor-not-allowed"
                  x-bind:class="btn.token || 'btc-soft'"
                  :disabled="btnLoading[i]"
                  @click="run(btn,i)"
                  x-ref="first"
                >
                  <span x-text="btn.label ?? 'OK'"></span>
                </button>
            </template>
          </div>
        </div>
      </div>
    </div>
  </template>
</div>
