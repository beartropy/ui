@php
    [$colorPreset, $sizePreset] = $getComponentPresets('avatar');

    $classes = implode(' ', [
        'inline-flex items-center justify-center rounded-full',
        $colorPreset['bg'] ?? '',
        $colorPreset['text'] ?? '',
        $colorPreset['border'] ?? '',
        $colorPreset['ring'] ?? '',
        $colorPreset['font'] ?? '',
        ($customSize ? $customSize : $sizePreset['avatar'] ?? ''),
        $attributes->get('class'),
    ]);
@endphp

<div class="flex items-center h-full">
    <div class="inline-block relative">
        @if($src)
            <img src="{{ $src }}" alt="{{ $alt ?? '' }}" {{ $attributes->merge(['class' => 'object-cover ' . $classes]) }}>
        @elseif($initials)
            <span {{ $attributes->merge(['class' => $classes]) }}>
                {{ $initials }}
            </span>
        @else
            <span {{ $attributes->merge(['class' => $classes]) }}>
                @if (trim($slot))
                    {{ $slot }}
                @else
                    {{-- SVG default --}}
                    <svg class="w-2/3 h-2/3 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <circle cx="12" cy="8" r="4"/>
                        <path d="M4 20c0-4 8-4 8-4s8 0 8 4"/>
                    </svg>
                @endif
            </span>
        @endif
        {{-- Slot status --}}
        @if(isset($status))
            <span class="absolute bottom-0 right-0 translate-x-1/4 translate-y-1/4">
                {!! $status !!}
            </span>
        @endif
    </div>
</div>
