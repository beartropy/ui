@php

    // Optional classes for "styled" mode
    $wrapperClass = $styled ? 'p-6' : '';
    $titleClass   = $styled ? 'text-xl font-semibold border-b border-gray-200 dark:border-gray-700 pb-3 text-gray-800 dark:text-gray-200' : '';
    $slotClass    = $styled ? 'my-4 text-gray-800 dark:text-gray-200' : '';
    $footerClass  = $styled ? 'flex justify-end items-center border-t border-gray-200 dark:border-gray-700 pt-3' : '';

    // Z-index class
    $zIndexClass = 'z-' . $zIndex;

    // Allow kebab-case attribute
    $closeOnClickOutside = ${'close-on-click-outside'} ?? $closeOnClickOutside;

    // Determine modal ID
    if ($attributes->wire('model')->value() !== false) {
        $modalId = $attributes->wire('model')->value();
    } else {
        $modalId = $id ?? 'modal-' . uniqid();
    }

    // Size classes
    $maxWidths = [
        'sm' => 'max-w-sm','md' => 'max-w-md','lg' => 'max-w-lg','xl' => 'max-w-xl',
        '2xl' => 'max-w-2xl','3xl' => 'max-w-3xl','4xl' => 'max-w-4xl','5xl' => 'max-w-5xl',
        '6xl' => 'max-w-6xl','7xl' => 'max-w-7xl','full' => 'max-w-full',
    ];
    $widthClass = $maxWidths[$maxWidth] ?? 'max-w-3xl';

    // Blur classes
    $blurLevels = [
        'none' => 'backdrop-blur-none','sm' => 'backdrop-blur-sm','md' => 'backdrop-blur-md',
        'lg' => 'backdrop-blur-lg','xl' => 'backdrop-blur-xl','2xl' => 'backdrop-blur-2xl','3xl' => 'backdrop-blur-3xl',
    ];
    $blurClass = $blurLevels[$blur] ?? 'backdrop-blur-xl';

    // Events
    $eventToOpen  = 'open-modal-' . $modalId;
    $eventToClose = 'close-modal-' . $modalId;

    // Variables to pass to the partial
    $modalVars = compact(
        'attributes','modalId','eventToOpen','eventToClose','widthClass','bgColor',
        'blurClass','zIndexClass','styled','titleClass','slotClass','footerClass',
        'closeOnClickOutside','showCloseButton','centered'
    );

@endphp

@if($teleport)
    <template x-teleport="{{ $teleportTarget }}">
        @include('beartropy-ui::partials.modal-root', $modalVars)
    </template>
@else
    @include('beartropy-ui::partials.modal-root', $modalVars)
@endif
