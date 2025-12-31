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

        return <<<HTML
            <link rel="stylesheet" href="/beartropy-ui-assets/beartropy-ui.css?v={$cssVersion}">
            <script src="/beartropy-ui-assets/beartropy-ui.js?v={$jsVersion}"></script>
        HTML;
    }
}
