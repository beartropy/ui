@props([
    'items',
    'titleClass'  => '',
    'itemClass'   => '',
    'activeClass' => '',
    'ulClass'     => '',
    'liClass'     => '',
    'iconClass'   => 'w-4 h-4 mr-2 inline-block',
    'level'       => 0,
    'mobile'      => false, // para distinguir estilos
])

<ul
    role="list"
    class="{{ $ulClass }} {{ $mobile ? 'p-2' : ($level > 0 ? 'ml-4 border-l border-slate-200 dark:border-slate-700 pl-2' : '') }}"
    x-data
>
    @foreach ($items as $node)
        <li class="{{ $liClass }}">
            {{-- TITULO DE SECCION (opcional, en cualquier nivel) --}}
            @if (!empty($node['title']))
                <h2 class="{{ $titleClass }} {{ $level === 0 ? 'mt-2 mb-1' : 'mt-1 mb-1 text-xs uppercase tracking-widest opacity-80' }}">
                    {{ $node['title'] }}
                </h2>
            @endif

            {{-- SUBMENU anidado --}}
            @if (!empty($node['items']))
                <x-beartropy-ui::menu
                    :items="$node['items']"
                    :title-class="$titleClass"
                    :item-class="$itemClass"
                    :active-class="$activeClass"
                    :ul-class="$ulClass"
                    :li-class="$liClass"
                    :icon-class="$iconClass"
                    :level="$level + 1"
                    :mobile="$mobile"
                />
            {{-- ENLACE --}}
            @elseif(isset($node['url'], $node['label']))
                @php
                    $pattern  = $node['route'] ?? ltrim(parse_url($node['url'], PHP_URL_PATH), '/');
                    $isActive = request()->is(ltrim($pattern, '/'));
                    $classes  = trim($itemClass . ' ' . ($isActive ? $activeClass : ''));
                @endphp
                <a href="{{ $node['url'] }}"
                   class="{{ $classes }} flex items-center gap-2"
                   wire:navigate
                   {{ $isActive ? 'aria-current="page"' : '' }}
                >
                    {{-- ÍCONO opcional --}}
                    @if (!empty($node['icon']))
                        <i class="{{ $iconClass }} {{ $node['icon'] }}"></i>
                    @endif
                    {{ $node['label'] }}

                    {{-- BADGE opcional --}}
                    @if (!empty($node['badge']))
                        <span class="{{ $node['badge']['class'] ?? '' }}">
                            {{ $node['badge']['text'] ?? '' }}
                        </span>
                    @endif

                    @if ($isActive)
                        <span class="sr-only">(current)</span>
                    @endif
                </a>
            @endif
        </li>
    @endforeach
</ul>
