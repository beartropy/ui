<?php

namespace Beartropy\Ui\Components;

class Tag extends BeartropyComponent
{
    public function __construct(
        public ?string $name = null,
        public ?string $label = null,
        public string $placeholder = 'Add tag...',
        public array $value = [],
        public array|string $separator = ',',
        public bool $disabled = false,
        public bool $unique = true,
        public ?int $maxTags = null,
        public ?string $help = null,
        public ?string $error = null,
        public ?string $start = null,
        public ?string $end = null,
        public ?string $size = null,
        public ?string $customError = null,
        public ?string $color = null,
        public bool $outline = false,
    ) {}

    public function render()
    {
        return view('beartropy-ui::tag');
    }

}
