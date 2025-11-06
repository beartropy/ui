@php
    [$colorPreset, $sizePreset, $shouldFill, $presetNames] = $getComponentPresets('fab');
    $icon = $icon ?? 'plus';
    $label = $label ?? 'Nuevo';
    $onlyMobile = $onlyMobile ?? false;
    $zIndex = $zIndex ?? 50;
    $right = $right ?? 8;
    $bottom = $bottom ?? 8;
@endphp

<div class="fixed bottom-{{ $bottom }} right-{{ $right }} z-{{ $zIndex }} {{ $onlyMobile ? '' : 'md:hidden' }}">
  <button
    type="button"
    {{ $attributes->merge([
      'class' => 'flex items-center justify-center  rounded-full shadow-lg transition '.$colorPreset['bg'] . ' ' . $colorPreset['text'] . ' ' . $colorPreset['bg_hover'] . ' ' . $sizePreset['fabButton']
    ]) }}
  >
    @if ($slot->isNotEmpty())
      {!! $slot !!}
    @else
        <x-beartropy-ui::icon name="{{$icon}}" class="{{$sizePreset['fabIcon']}}" />
    @endif
  </button>
</div>
