<?php

namespace Beartropy\Ui\Components;

/**
 * ThemeHead component.
 *
 * Outputs a blocking inline <script> that applies the saved dark/light theme
 * before any CSS or body content renders, preventing FOUC (Flash of Unstyled
 * Content). Must be placed in the <head> of your layout.
 *
 * Also hooks into Livewire's `livewire:navigated` event to re-apply the theme
 * immediately after SPA navigation.
 *
 * Usage:
 *   <head>
 *       <x-bt-theme-head />
 *       ...
 *   </head>
 */
class ThemeHead extends BeartropyComponent
{
    public function render(): \Illuminate\Contracts\View\View
    {
        return view('beartropy-ui::theme-head');
    }
}
