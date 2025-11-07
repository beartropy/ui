<?php

namespace Beartropy\Ui\Support;

class BeartropyAssets
{
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
