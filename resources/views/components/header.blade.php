@props([
    'logo' => null,
    'title' => env('APP_NAME', 'Beartropy UI'),
    'fixed' => false,
    'mini' => false, // Lo recibís del layout o Alpine
    'zIndex' => 50,
    'class' => null,
    'actions' => null,
])

@php
    $marginLeft = $mini ? 'ml-20' : 'ml-64';
@endphp

<div
    @if($fixed)
        :class="'fixed top-0 right-0 w-full z-' . $zIndex . ' ' . $marginLeft . ' h-14 flex items-center transition-all duration-300 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 shadow-md'"
    @else
        :class="'w-full ' . $marginLeft . ' h-14 flex items-center transition-all duration-300 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 shadow-md'"
    @endif
>
    <div class="flex items-center gap-4">
        @if($logo)
            <img src="{{ $logo }}" alt="Logo" class="h-8">
        @endif
        @if($title)
            <span class="ml-2 text-lg font-semibold text-gray-700 dark:text-gray-200">{{ $title }}</span>
        @endif
    </div>
    <div class="flex-1 flex items-center justify-center">
        {{ $slot ?? '' }}
    </div>
    @if($actions)
        <div class="flex items-center gap-3 ml-auto">
            {{ $actions }}
        </div>
    @endif
</div>
