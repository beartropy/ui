@php
    [$colorPreset, $sizePreset, $shouldFill, $presetNames] = $getComponentPresets('fab');
    $icon = $icon ?? 'plus';
    $label = $label ?? 'Nuevo';
    $onlyMobile = $onlyMobile ?? false;
    $zIndex = $zIndex ?? 50;
    $right = $right ?? "1rem";
    $bottom = $bottom ?? "1rem";

    $isLink = $attributes->has('href');

    $tag = $isLink ? 'a' : 'button';
@endphp

<div class="fixed {{ $onlyMobile ? 'md:hidden' : '' }}" style="right: {{ $right }}; bottom: {{ $bottom }}; z-index: {{ $zIndex }};">
  <{{ $tag }}
    {{ $attributes->merge([
      'class' => 'flex items-center justify-center  rounded-full shadow-lg transition '.$colorPreset['bg'] . ' ' . $colorPreset['text'] . ' ' . $colorPreset['bg_hover'] . ' ' . $sizePreset['fabButton']
    ]) }}
    @if ($isLink)
      href="{{ $attributes->get('href') }}"
    @endif

    {{ $attributes->except('href', 'class') }}
  >
    @if ($slot->isNotEmpty())
      {!! $slot !!}
    @else
        <x-beartropy-ui::icon name="{{$icon}}" class="{{$sizePreset['fabIcon']}}" />
    @endif
  </{{ $tag }}>
</div>
