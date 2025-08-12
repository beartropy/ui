<?php

namespace Beartropy\Ui\Components;

class Lookup extends Input
{
    // No usar $options nunca


    public function __construct(
        public $options = [],
        public $optionLabel = "name",
        public $optionValue = "id"
    ) {
        $this->options = collect($this->options)->map(function ($item) {
            if(!isset($item[$this->optionValue]) || !isset($item[$this->optionLabel])) {
                return null;
            }
            return [
                'id' => $item[$this->optionValue],
                'name' => $item[$this->optionLabel],
            ];
        })->filter();
        parent::__construct();
    }


    public function render()
    {
        return view('beartropy-ui::lookup');
    }
}
