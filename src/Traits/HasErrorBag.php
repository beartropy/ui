<?php

namespace Beartropy\Ui\Traits;

trait HasErrorBag
{

    public function getErrorState($error = null, $attributes = null, $errors = null)
    {

        $attributes = $attributes ?: $this->attributes;
        $errors = $errors ?: session('errors', app('view')->shared('errors', new \Illuminate\Support\MessageBag));

        $wireModel = $attributes->wire('model')->value();
        $errorFromBag = $wireModel && $errors->has($wireModel) ? $errors->first($wireModel) : null;
        $finalError = $errorFromBag ?: $error;
        $hasError = !!$finalError;

        return [
            '__bt_wireModel'  => $wireModel,
            '__bt_finalError' => $finalError,
            '__bt_hasError'   => $hasError,
        ];
    }
}
