@props([
    'start' => null,
    'end' => null,
    'bg' => 'bg-light dark:bg-gray-900',
])

<header {{ $attributes->merge(['class' => 'fixed top-0 left-0 right-0 p-1 ' . $bg . ' z-20']) }}>
    <!-- Center / Search Area -->
    @if($start)
        <div class="absolute inset-x-0 top-1/2 -translate-y-1/2 flex justify-center pointer-events-none">
            <div class="pointer-events-auto w-full max-w-lg mt-0.5">
                {{ $start }}
            </div>
        </div>
    @endif

    <!-- Right Actions -->
    <div class="flex justify-end items-center space-x-3 mr-4 h-10">
        {{ $end ?? $slot }}
    </div>
</header>
