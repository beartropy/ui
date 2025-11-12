<?php

namespace Beartropy\Ui\Http\Controllers;

use Tighten\Ziggy\BladeRouteGenerator;
use Illuminate\Support\Facades\Response;

class AssetController
{
    public function beartropyAssets($file)
    {
        // Permití extensiones seguras
        $allowedExtensions = ['js', 'css', 'svg', 'map', 'woff2', 'woff', 'ttf'];
        $extension = pathinfo($file, PATHINFO_EXTENSION);
        if (!in_array($extension, $allowedExtensions)) {
            abort(403, 'File type not allowed');
        }

        // Detectá si es JS o CSS
        $basePath = __DIR__ . '/../../../resources/';
        // Opcional: Podés chequear subdirectorios (js, css, fonts, etc.)
        if (file_exists($basePath . 'js/' . $file)) {
            $path = $basePath . 'js/' . $file;
        } elseif (file_exists($basePath . 'css/' . $file)) {
            $path = $basePath . 'css/' . $file;
        } else {
            abort(404);
        }

        $contentTypes = [
            'js'   => 'application/javascript',
            'css'  => 'text/css',
            'svg'  => 'image/svg+xml',
            'map'  => 'application/json',
            'woff2'=> 'font/woff2',
            'woff' => 'font/woff',
            'ttf'  => 'font/ttf',
        ];

        return Response::file($path, [
            'Content-Type' => $contentTypes[$extension] ?? 'text/plain',
            'Cache-Control' => 'public, max-age=604800, immutable',
        ]);
    }

    public function ziggy()
    {
        if (!class_exists(BladeRouteGenerator::class)) {
            abort(500, 'Ziggy is not installed.');
        }

        // 1) Generamos el blob que normalmente Ziggy imprime dentro de <script>...</script>
        $html = app(BladeRouteGenerator::class)->generate();

        // 2) Le quitamos las etiquetas <script> envolventes para quedarnos con JS puro
        $js = preg_replace('#^\s*<script[^>]*>|</script>\s*$#i', '', trim($html));

        // 3) Envolvemos con un guard para que viva una sola vez en window
        $wrapped = <<<JS
;(function(){
  if (window.__ziggy_loaded) return;
  window.__ziggy_loaded = true;
  {$js}
})();
JS;

        return Response::make($wrapped, 200, [
            'Content-Type'  => 'application/javascript; charset=UTF-8',
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }
}
