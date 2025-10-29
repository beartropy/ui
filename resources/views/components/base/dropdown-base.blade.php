{{-- resources/views/components/base/dropdown-base.blade.php --}}
@props([
    'placement' => 'left',
    'side'      => 'bottom',
    'width'     => '',           // ej: 'min-w-[12rem]' o 'w-64'
    'presetFor' => 'dropdown',

    // ⚙️ Nuevos props configurables
    'autoFit'   => true,         // ✅ default = se adapta al viewport y hace flip (como antes)
    'autoFlip'  => true,         // permite invertir top/bottom según espacio
    'maxHeight' => null,         // altura ideal si se usa autoFit, o fija si autoFit=false
    'overflow'  => null,         // null => se resuelve según autoFit/maxHeight, o 'visible'|'auto'|'scroll'
])

@php
    [$colorPreset, $sizePreset] = $getComponentPresets($presetFor ?? 'dropdown');

    $alignment = match($placement ?? 'left') {
        'right'  => 'right-0',
        'center' => 'left-1/2 -translate-x-1/2',
        default  => 'left-0',
    };

    $preferredSide = ($side ?? 'bottom') === 'top' ? 'top' : 'bottom';

    // 🧭 Resolver overflow automáticamente
    // autoFit=true  => overflow:auto (scroll si hace falta)
    // autoFit=false => visible salvo que haya maxHeight
    $resolvedOverflow = $overflow
        ?? ($autoFit ? 'auto' : ($maxHeight ? 'auto' : 'visible'));

    $overflowClass = match($resolvedOverflow) {
        'auto'   => 'overflow-y-auto',
        'scroll' => 'overflow-y-scroll',
        default  => 'overflow-visible',
    };

    $thinScrollbar = in_array($resolvedOverflow, ['auto','scroll'], true)
        ? 'beartropy-thin-scrollbar'
        : '';

    // estilo inicial si no hay autoFit
    $initialStyle = (!$autoFit && $maxHeight)
        ? "max-height:{$maxHeight}px;"
        : '';
@endphp

<div
    x-data="{
        sideLocal: '{{ $preferredSide }}',
        maxStyle: '{{ $initialStyle }}',
        _reposition() {
            if (!{{ $autoFit ? 'true' : 'false' }}) return;

            const anchor = $el.parentElement;
            if (!anchor) return;

            const rect = anchor.getBoundingClientRect();
            const vh   = window.innerHeight || document.documentElement.clientHeight;

            const spaceBelow = vh - rect.bottom;
            const spaceAbove = rect.top;
            const margin = 16;
            const ideal  = {{ $maxHeight ? (int) $maxHeight : 300 }};
            const need   = Math.min(ideal, $el.scrollHeight || ideal);

            if ({{ $autoFlip ? 'true' : 'false' }}) {
                if (this.sideLocal === 'bottom') {
                    this.sideLocal = (spaceBelow < 160 && spaceAbove > spaceBelow) ? 'top' : 'bottom';
                } else {
                    this.sideLocal = (spaceAbove < 160 && spaceBelow > spaceAbove) ? 'bottom' : 'top';
                }
            }

            const room = this.sideLocal === 'bottom' ? spaceBelow : spaceAbove;
            const maxH = Math.max(140, Math.min(need, room - margin));
            this.maxStyle = `max-height:${maxH}px;`;
        },
        _bindListeners() {
            if (!{{ $autoFit ? 'true' : 'false' }}) return;
            const cb = () => { if (this.$data.open) this._reposition(); };
            window.addEventListener('resize', cb, { passive:true });
            window.addEventListener('scroll', cb, { passive:true });
        }
    }"
    x-init="_bindListeners()"
    x-effect="open && $nextTick(() => _reposition())"
    x-show="open"
    x-collapse
    x-transition:enter="transition ease-out duration-150"
    x-transition:enter-start="opacity-0 scale-95"
    x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-100"
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-95"
    @click.outside="typeof onDropdownClose === 'function' ? onDropdownClose() : open = false"
    class="absolute z-50 {{ $alignment }} {{ $width }}
           rounded-lg
           {{ $colorPreset['dropdown_border'] ?? '' }}
           {{ $colorPreset['dropdown_bg'] }}
           {{ $colorPreset['dropdown_shadow'] }}
           {{ $overflowClass }}
           {{ $thinScrollbar }}"
    :class="sideLocal === 'top' ? 'bottom-full mb-1 origin-bottom' : 'top-full mt-1 origin-top'"
    :style="`min-width:8rem; ${maxStyle}`"
>
    {{ $slot }}
</div>
