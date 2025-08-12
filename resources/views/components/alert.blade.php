@php
    [$colorPreset, $sizePreset] = $getComponentPresets('alert');
@endphp

<div
    x-data="{ open: true }"
    x-show="open"
    x-transition.opacity.duration.300ms
    class="{{ $colorPreset['main'] ?? 'flex items-start gap-3 p-4 rounded-lg shadow-sm' }} {{$class}}"
    role="alert"
>

    @if($noIcon !== true && (isset($colorPreset['icon']) || isset($icon)))
        {{-- Icon --}}
        <div  class="{{ $colorPreset['icon_wrapper'] ?? 'mt-0.5 flex-shrink-0' }}">
            @if(isset($icon))
                @if(is_string($icon))
                    <x-beartropy-ui::icon :name="$icon" class="{{ $colorPreset['icon_class'] ?? 'w-7 h-7' }}" />
                @else
                    {{ $icon }}
                @endif
            @else
                @if(isset($colorPreset['icon']) && $colorPreset['icon'])
                    <x-beartropy-ui::icon :name="$preset->icon" class="{{ $preset->icon_class ?? 'w-7 h-7' }}" />
                @endif
            @endif
        </div>
    @endif

    <div class="{{ $colorPreset['content'] ?? 'flex-1 min-w-0 mt-0.5' }}">
        {{-- Título --}}
        {{-- Título opcional --}}
        @if (isset($title))
            <div class="{{ $colorPreset['title'] ?? 'font-bold mb-1 text-sm leading-tight' }}">
                {{ $title }}
            </div>
        @endif

        <div class="{{ $colorPreset['slot'] ?? 'text-sm leading-normal' }}">
            {{ $slot }}
        </div>
    </div>

    {{-- Dismiss --}}
    @if($dismissible)
        <button type="button" @click="open = false"
            class="ml-3 rounded-full hover:bg-black/10 dark:hover:bg-white/10 p-1 transition focus:outline-none"
            aria-label="Cerrar"
        >
            <x-beartropy-ui::icon name="x-mark" class="w-5 h-5" />
        </button>
    @endif
</div>
