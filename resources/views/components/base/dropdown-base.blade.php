@php
    [$colorPreset, $sizePreset] = $getComponentPresets($presetFor ?? 'dropdown');

    $alignment = match($placement ?? 'left') {
        'right'  => 'right-0',
        'center' => 'left-1/2 -translate-x-1/2',
        default  => 'left-0',
    };

    // lado preferido si entra (fallback)
    $preferredSide = ($side ?? 'bottom') === 'top' ? 'top' : 'bottom';
@endphp

<div
    x-data="{
        sideLocal: '{{ $preferredSide }}',
        maxStyle: '',
        _reposition() {
            // anchor = contenedor del trigger (padre inmediato del dropdown)
            const anchor = $el.parentElement;
            if (!anchor) return;

            const rect = anchor.getBoundingClientRect();
            const vh   = window.innerHeight || document.documentElement.clientHeight;

            const spaceBelow = vh - rect.bottom;
            const spaceAbove = rect.top;
            const margin = 16;           // respiración
            const ideal  = 300;          // alto deseado
            const need   = Math.min(ideal, $el.scrollHeight || ideal);

            // Elegir lado: respetar preferencia si hay espacio razonable; si no, flip
            if (this.sideLocal === 'bottom') {
                this.sideLocal = (spaceBelow < 160 && spaceAbove > spaceBelow) ? 'top' : 'bottom';
            } else {
                this.sideLocal = (spaceAbove < 160 && spaceBelow > spaceAbove) ? 'bottom' : 'top';
            }

            const room = this.sideLocal === 'bottom' ? spaceBelow : spaceAbove;
            const maxH = Math.max(140, Math.min(need, room - margin));
            this.maxStyle = `max-height:${maxH}px; overflow:auto;`;
        },
        _bindListeners() {
            const cb = () => { if (this.$data.open) this._reposition(); };
            window.addEventListener('resize', cb, { passive:true });
            window.addEventListener('scroll', cb, { passive:true });
        }
    }"
    x-init="_bindListeners()"
    x-effect="open && $nextTick(() => _reposition())"
    x-show="open"
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
           beartropy-thin-scrollbar"
    :class="sideLocal === 'top' ? 'bottom-full mb-1 origin-bottom' : 'top-full mt-1 origin-top'"
    :style="`min-width:8rem; ${maxStyle}`"
>
    {{ $slot }}
</div>
