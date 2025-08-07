<?php

namespace Beartropy\Ui\Support;

class BeartropyAssets
{
    public function render()
    {
        // Podés personalizar esto con los scripts que quieras inyectar
        return <<<'HTML'
            <link rel="stylesheet" href="/beartropy-ui-assets/beartropy-ui.css">
            <script src="/beartropy-ui-assets/beartropy-ui.js"></script>
        HTML;
    }
}
