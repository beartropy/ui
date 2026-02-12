@php
    [$colorPreset, $sizePreset, $shouldFill, $presetNames] = $getComponentPresets('fab');
    $icon = $icon ?? 'plus';
    $label = $label ?? __('beartropy-ui::ui.new');
    $onlyMobile = $onlyMobile ?? false;
    $zIndex = $zIndex ?? 50;
    $right = $right ?? "1rem";
    $bottom = $bottom ?? "1rem";

    $isLink = $attributes->has('href');
    $tag = $isLink ? 'a' : 'button';

    $classes = 'flex items-center justify-center rounded-full shadow-lg transition cursor-pointer focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 '
        . $colorPreset['bg'] . ' ' . $colorPreset['text'] . ' ' . $colorPreset['bg_hover'] . ' ' . $sizePreset['fabButton'];
@endphp

<div class="fixed {{ $onlyMobile ? 'md:hidden' : '' }}" style="right: {{ $right }}; bottom: {{ $bottom }}; z-index: {{ $zIndex }};">
  <{{ $tag }}
    {{ $attributes->merge(['class' => $classes]) }}
    aria-label="{{ $label }}"
    @if (! $isLink) type="button" @endif
  >
    @if ($slot->isNotEmpty())
      {!! $slot !!}
    @else
        <x-beartropy-ui::icon name="{{$icon}}" class="{{$sizePreset['fabIcon']}}" />
    @endif
  </{{ $tag }}>
</div>
