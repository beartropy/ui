@props([
    'id' => null,
    'maxWidth' => '3xl',
    'zIndex' => '30',
    'blur' => 'none',
    'bgColor' => 'bg-white dark:bg-gray-900',
    'closeOnClickOutside' => true,
    'styled' => false,
    'showCloseButton' => true,

    'centered' => false,
    'teleport' => true,
    'teleportTarget' => 'body',
])

@php

    // Clases opcionales para modo "styled"
    $wrapperClass = $styled ? 'p-6' : '';
    $titleClass   = $styled ? 'text-xl font-semibold border-b border-gray-200 dark:border-gray-700 pb-3 text-gray-800 dark:text-gray-200' : '';
    $slotClass    = $styled ? 'my-4 text-gray-800 dark:text-gray-200' : '';
    $footerClass  = $styled ? 'flex justify-end items-center border-t border-gray-200 dark:border-gray-700 pt-3' : '';

    // z-index (mantengo tu lógica)
    $zIndexClass = 'z-' . $zIndex;

    // Permitir kebab-case en el atributo
    $closeOnClickOutside = ${'close-on-click-outside'} ?? $closeOnClickOutside;

    // Determinar ID del modal
    if ($attributes->wire('model')->value() !== false) {
        $modalId = $attributes->wire('model')->value();
    } else {
        $modalId = $id ?? 'modal-' . uniqid();
    }

    // Clases de tamaño
    $maxWidths = [
        'sm' => 'max-w-sm','md' => 'max-w-md','lg' => 'max-w-lg','xl' => 'max-w-xl',
        '2xl' => 'max-w-2xl','3xl' => 'max-w-3xl','4xl' => 'max-w-4xl','5xl' => 'max-w-5xl',
        '6xl' => 'max-w-6xl','7xl' => 'max-w-7xl','full' => 'max-w-full',
    ];
    $widthClass = $maxWidths[$maxWidth] ?? 'max-w-3xl';

    // Clases de blur
    $blurLevels = [
        'none' => 'backdrop-blur-none','sm' => 'backdrop-blur-sm','md' => 'backdrop-blur-md',
        'lg' => 'backdrop-blur-lg','xl' => 'backdrop-blur-xl','2xl' => 'backdrop-blur-2xl','3xl' => 'backdrop-blur-3xl',
    ];
    $blurClass = $blurLevels[$blur] ?? 'backdrop-blur-xl';

    // Eventos
    $eventToOpen  = 'open-modal-' . $modalId;
    $eventToClose = 'close-modal-' . $modalId;

    // Variables a pasar al partial
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
