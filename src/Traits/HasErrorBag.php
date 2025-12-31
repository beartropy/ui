<?php

namespace Beartropy\Ui\Traits;

/**
 * Trait HasErrorBag.
 *
 * Provides utilities to check for validation errors on component properties.
 */
trait HasErrorBag
{
    /**
     * Retrieve the error state for a given attribute or context.
     *
     * @param string|null $error      Direct error message.
     * @param \Illuminate\View\ComponentAttributeBag|null $attributes Component attributes.
     * @param \Illuminate\Support\MessageBag|null       $errors     Global errors.
     *
     * @return array{__bt_wireModel: string|null, __bt_finalError: string|null, __bt_hasError: bool}
     */
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
