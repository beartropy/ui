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
     * Determine the error state for a specific field.
     *
     * Checks both the session error bag and specific error attributes.
     * Looks up errors by `wire:model` value or `name` attribute.
     *
     * @param \Illuminate\View\ComponentAttributeBag|null $attributes Component attributes.
     * @param \Illuminate\Support\ViewErrorBag|\Illuminate\Support\MessageBag|null $errors Global error bag.
     * @param string|null                                 $error      Specific error message override.
     *
     * @return array{0: bool, 1: string|null} [hasError, firstErrorMessage]
     */
    public function getErrorState(
        ?\Illuminate\View\ComponentAttributeBag $attributes = null,
        \Illuminate\Support\ViewErrorBag|\Illuminate\Support\MessageBag|null $errors = null,
        ?string $error = null,
    ): array {
        $attributes = $attributes ?: $this->attributes;
        $errors = $errors ?: session('errors', app('view')->shared('errors', new \Illuminate\Support\ViewErrorBag));

        $wireModel = $attributes->wire('model')->value();
        $inputName = $attributes->get('name') ?? $wireModel;

        $errorFromBag = $inputName && $errors->has($inputName) ? $errors->first($inputName) : null;
        $finalError = $errorFromBag ?: $error;
        $hasError = (bool) $finalError;

        return [$hasError, $finalError];
    }
}
