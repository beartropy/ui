@php
    $size = $getSizePreset();
    $iconSize = $size['iconSize'] ?? 'h-5 w-5';
    $iconData = $getClasses($iconSize);
@endphp

@if (isset($iconData->iconComponent))
    @if ($iconData->set === 'beartropy')
        @include($iconData->iconComponent, [
            'attributes' => $attributes->merge(['class' => $iconData->allClasses]),
        ])
    @else
        <x-dynamic-component :component="$iconData->iconComponent" :class="$iconData->allClasses" {{ $attributes }} />
    @endif
@elseif($iconData->set === 'fontawesome')
    <i class="{{ $iconData->fa }}" {{ $attributes }}></i>
@else
    <span class="text-red-600" {{ $attributes }}>?</span>
@endif
