@props([
    'iconClass' => 'w-4 h-4 shrink-0',
    'level'     => 0,
    'mobile'    => false,
])

@php
    [$colorPreset, , , $presetNames] = $getComponentPresets('menu');
    $resolvedColor = $presetNames['color'] ?? 'orange';
    $titleClass    = $colorPreset['title']  ?? 'font-medium text-orange-500 font-display dark:text-orange-400';
    $itemClass     = $colorPreset['item']   ?? 'transition inline-flex items-center gap-x-2 text-gray-500 dark:text-gray-400 hover:text-orange-500 dark:hover:text-orange-400';
    $activeClass   = $colorPreset['active'] ?? 'text-orange-500 dark:text-orange-400 font-semibold';
@endphp

<ul
    role="list"
    class="{{ $ulClass }} {{ $mobile ? 'p-2' : ($level > 0 ? 'ml-4 border-l border-slate-200 dark:border-slate-700 pl-2' : '') }}"
>
    @foreach ($items as $node)
        <li class="{{ $liClass }}">
            {{-- Section title --}}
            @if (!empty($node['title']))
                <h2 class="{{ $titleClass }} {{ $level === 0 ? 'mt-2 mb-1' : 'mt-1 mb-1 text-xs uppercase tracking-widest opacity-80' }}">
                    {{ $node['title'] }}
                </h2>
            @endif

            {{-- Nested submenu --}}
            @if (!empty($node['items']))
                <x-beartropy-ui::menu
                    :items="$node['items']"
                    :color="$resolvedColor"
                    :ul-class="$ulClass"
                    :li-class="$liClass"
                    :icon-class="$iconClass"
                    :level="$level + 1"
                    :mobile="$mobile"
                />
            {{-- Link item --}}
            @elseif(isset($node['url'], $node['label']))
                @php
                    $pattern  = $node['route'] ?? ltrim(parse_url($node['url'], PHP_URL_PATH), '/');
                    $isActive = request()->is($pattern ?: '/');
                    $classes  = trim($itemClass . ' ' . ($isActive ? $activeClass : ''));
                    $iconHtml = $renderIcon($node['icon'] ?? '', $iconClass);
                @endphp
                <a href="{{ $node['url'] }}"
                   class="{{ $classes }} flex items-center gap-2"
                   wire:navigate
                   @if ($isActive) aria-current="page" @endif
                >
                    @if ($iconHtml)
                        {!! $iconHtml !!}
                    @endif

                    {{ $node['label'] }}

                    @if (!empty($node['badge']))
                        <span class="{{ $node['badge']['class'] ?? '' }}">
                            {{ $node['badge']['text'] ?? '' }}
                        </span>
                    @endif

                    @if ($isActive)
                        <span class="sr-only">({{ __('beartropy-ui::ui.current') }})</span>
                    @endif
                </a>
            @endif
        </li>
    @endforeach
</ul>
