@php
    $method = $init ?? $function;
    $lines = (int) $lines;
    $tagName = $tag;

    // Detectar si el usuario pasó clases tipo h-*, min-h-*, max-h-*
    $wrapperClass = $attributes->get('class', '');
    $wrapperHasHeight = preg_match('/\b(h-|min-h-|max-h-)/', $wrapperClass);

    // Fallback solo si es un único bloque y no hay altura externa
    // min-h-* para que aunque el wrapper colapse, el skeleton se vea
    $fallbackMinH = (!$wrapperHasHeight && $lines === 1)
        ? 'min-h-[0.75rem]'   // equivalente a h-3 (12px aprox)
        : '';

    $roundedClass = match ($rounded) {
        'none' => 'rounded-none',
        'sm'   => 'rounded-sm',
        'md'   => 'rounded-md',
        'lg'   => 'rounded-lg',
        'xl'   => 'rounded-xl',
        'full' => 'rounded-full',
        default => 'rounded-lg',
    };
@endphp

<{{ $tagName }}
    @if($method)
        wire:init="{{ $method }}"
    @endif
    {{ $attributes->class(['relative']) }}
>
    {{-- Mientras carga --}}
<{{ $tagName }}
    @if($method)
        wire:init="{{ $method }}"
    @endif
    {{ $attributes->class(['relative']) }}
>
    {{-- Mientras carga --}}
    <div wire:loading class="w-full h-full">
        @if ($shape == 'none' && $lines > 1)
            {{-- Párrafo de varias líneas --}}
            <div class="flex flex-col justify-center w-full h-full space-y-2">
                @for ($i = 0; $i < $lines; $i++)
                    @php
                        $widthClass = match ($i) {
                            0 => 'w-full',
                            1 => 'w-4/5',
                            2 => 'w-3/5',
                            default => 'w-full',
                        };
                    @endphp

                    <div class="h-3 {{ $widthClass }} {{ $roundedClass }} animate-pulse bg-slate-200/80 dark:bg-slate-700/60">
                        &nbsp;
                    </div>
                @endfor
            </div>
        @else
            @if($shape == 'card')
                {{-- CARD: skeleton tipo card con título + líneas --}}
                <div class="w-full h-full {{ $roundedClass }} animate-pulse bg-slate-200/90 dark:bg-slate-700/70 p-3 flex flex-col justify-center">
                    <h3 class="h-4 bg-slate-300/80 dark:bg-slate-600/80 rounded w-1/2 mb-4"></h3>

                    @if($lines > 1)
                        @for ($i = 1; $i <= $lines; $i++)
                            @php
                                $widthClass = match ($i) {
                                    1 => 'w-full',
                                    2 => 'w-4/5',
                                    3 => 'w-3/5',
                                    default => 'w-full',
                                };
                            @endphp

                            <p class="h-3 bg-slate-300/80 dark:bg-slate-600/80 {{ $widthClass }} rounded {{ ($lines != $i) ? 'mb-2.5' : '' }}"></p>
                        @endfor
                    @else
                        <p class="h-3 bg-slate-300/80 dark:bg-slate-600/80 rounded w-3/4 mb-2.5"></p>
                        <p class="h-3 bg-slate-300/80 dark:bg-slate-600/80 rounded w-4/5 mb-2.5"></p>
                        <p class="h-3 bg-slate-300/80 dark:bg-slate-600/80 rounded w-full mb-2.5"></p>
                    @endif
                </div>

            @elseif($shape == 'rectangle')
                {{-- RECTANGLE: bloque sólido simple --}}
                <div class="w-full h-full {{ $roundedClass }} animate-pulse bg-slate-200/90 dark:bg-slate-700/70">
                    &nbsp;
                </div>

            @elseif($shape == 'image')
                {{-- IMAGE: recuadro con ícono de imagen dentro --}}
                <div class="w-full h-full {{ $roundedClass }} animate-pulse bg-slate-200/90 dark:bg-slate-700/70 flex items-center justify-center">
                    <div class="aspect-[4/3] w-3/4 max-h-full rounded-md bg-slate-300/70 dark:bg-slate-600/70 flex items-center justify-center overflow-hidden">
                        <svg
                            class="w-2/3 h-2/3 text-slate-400 dark:text-slate-500"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.5"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <rect x="3" y="5" width="18" height="14" rx="2" ry="2" />
                            <circle cx="9" cy="11" r="1.7" />
                            <path d="M21 17l-5-5-4 4-3-3-4 4" />
                        </svg>
                    </div>
                </div>

            @elseif($shape == 'table')
                {{-- TABLE: header + varias filas --}}
                <div class="w-full h-full {{ $roundedClass }} animate-pulse bg-slate-200/70 dark:bg-slate-700/70 p-3 flex flex-col space-y-2">
                    {{-- Header --}}
                    <div class="flex space-x-2 mb-1">
                        @for ($c = 0; $c < $cols; $c++)
                            <div class="h-3 flex-1 rounded bg-slate-300/80 dark:bg-slate-600/80"></div>
                        @endfor
                    </div>

                    {{-- Filas --}}
                    @for ($r = 0; $r < $rows; $r++)
                        <div class="flex space-x-2">
                            @for ($c = 0; $c < $cols; $c++)
                                <div class="h-3 flex-1 rounded bg-slate-300/70 dark:bg-slate-600/70"></div>
                            @endfor
                        </div>
                    @endfor
                </div>

            @else
                {{-- Fallback: bloque único simple cuando shape="none" y lines <= 1 --}}
                <div class="w-full h-full {{ $fallbackMinH }} {{ $roundedClass }} animate-pulse bg-slate-200/80 dark:bg-slate-700/60">
                    &nbsp;
                </div>
            @endif
        @endif
    </div>


    {{-- Cuando termina de cargar --}}
    <div wire:loading.remove class="w-full h-full">
        {{ $slot }}
    </div>
</{{ $tagName }}>
