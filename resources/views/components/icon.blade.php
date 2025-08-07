@php
$size = $getSizePreset();
$iconSize = $size['iconSize'] ?? 'h-5 w-5';
$iconData = $getClasses($iconSize);
@endphp

@if(isset($iconData->iconComponent))
    <x-dynamic-component :component="$iconData->iconComponent" :class="$iconData->allClasses" />
@elseif($iconData->set === 'fontawesome')
    <i class="{{ $iconData->fa }}"></i>
@else
    <span class="text-red-600">?</span>
@endif
