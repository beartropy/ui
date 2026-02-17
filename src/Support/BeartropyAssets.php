<?php

namespace Beartropy\Ui\Support;

/**
 * Beartropy Assets Manager.
 *
 * Handles the rendering of CSS and JS assets for the UI package.
 * Usually invoked via the `@BeartropyAssets` Blade directive.
 */
class BeartropyAssets
{
    /**
     * Render the HTML tags for CSS and JS assets.
     *
     * Appends version query parameters based on file modification time for cache busting.
     *
     * @return string HTML script and link tags.
     */
    public function render()
    {
        $cssPath = public_path('beartropy-ui-assets/beartropy-ui.css');
        $jsPath  = public_path('beartropy-ui-assets/beartropy-ui.js');

        $cssVersion = file_exists($cssPath) ? filemtime($cssPath) : time();
        $jsVersion  = file_exists($jsPath) ? filemtime($jsPath) : time();

        $themeScript = <<<'THEME'
            <script>(function(){var d=document.documentElement,s=localStorage.getItem('theme'),k=s==='dark'||(s!=='light'&&window.matchMedia('(prefers-color-scheme:dark)').matches);d.classList.toggle('dark',k);d.style.colorScheme=k?'dark':'light';window.__btThemeNavigated=true;document.addEventListener('livewire:navigated',function(){var s=localStorage.getItem('theme'),k=s==='dark'||(s!=='light'&&window.matchMedia('(prefers-color-scheme:dark)').matches);d.classList.toggle('dark',k);d.style.colorScheme=k?'dark':'light';});})();</script>
        THEME;

        return <<<HTML
            {$themeScript}
            <link rel="stylesheet" href="/beartropy-ui-assets/beartropy-ui.css?v={$cssVersion}">
            <script src="/beartropy-ui-assets/beartropy-ui.js?v={$jsVersion}"></script>
        HTML;
    }
}
