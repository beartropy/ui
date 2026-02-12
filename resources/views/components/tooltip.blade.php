<span
    x-data="{
        show: false,
        top: 0,
        left: 0,
        ready: false,
        timeout: null,
        calculatePosition() {
            const target = $refs.trigger;
            const rect = target.getBoundingClientRect();

            // Calculate position relative to the full document
            const scrollY = window.scrollY;
            const scrollX = window.scrollX;

            switch ('{{ $position }}') {
                case 'top':
                    this.top = rect.top + scrollY - 8;
                    this.left = rect.left + scrollX + rect.width / 2;
                    break;
                case 'bottom':
                    this.top = rect.bottom + scrollY + 8;
                    this.left = rect.left + scrollX + rect.width / 2;
                    break;
                case 'left':
                    this.top = rect.top + scrollY + rect.height / 2;
                    this.left = rect.left + scrollX - 8;
                    break;
                case 'right':
                default:
                    this.top = rect.top + scrollY + rect.height / 2;
                    this.left = rect.right + scrollX + 8;
                    break;
            }
        },
        transform() {
            switch ('{{ $position }}') {
                case 'top': return 'translateX(-50%) translateY(-100%)';
                case 'bottom': return 'translateX(-50%)';
                case 'left': return 'translateX(-100%) translateY(-50%)';
                case 'right':
                default: return 'translateY(-50%)';
            }
        },
        showTooltip() {
            clearTimeout(this.timeout);
            this.timeout = setTimeout(() => {
                this.calculatePosition();
                this.ready = true;
                this.show = true;
            }, {{ $delay }});
        },
        hideTooltip() {
            clearTimeout(this.timeout);
            this.show = false;
            // Small delay to reset ready after the exit animation
            setTimeout(() => { this.ready = false }, 300);
        }
    }"
    @mouseenter="showTooltip()"
    @mouseleave="hideTooltip()"
    @scroll.window="hideTooltip()"
    class="inline-block"
>
    <span x-ref="trigger" class="inline align-baseline leading-none cursor-help">
        {{ $slot }}
    </span>

    <template x-teleport="body">
        <div
            x-show="show && ready"
            x-cloak
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="absolute z-[9999] px-3 py-1.5 text-xs rounded pointer-events-none whitespace-nowrap overflow-hidden backdrop-blur-sm shadow-lg
                text-white bg-black/80
                dark:text-slate-800 dark:bg-white/90 dark:font-semibold"
            :style="`top: ${top}px; left: ${left}px; transform: ${transform()}`"
        >
            {{ $label }}
        </div>
    </template>
</span>