<?php

namespace App\View\Components;

class Select extends Control
{
    public $options;

    public $multiple;

    public $placeholder;

    public function __construct($name, $id = null, $value = '', $label = '', $description = '', $bag = 'default', $options = [], $multiple = false, $placeholder = '')
    {
        parent::__construct($name, $id, $value, $label, $description, $bag);
        $this->placeholder = $placeholder;
        $this->multiple = $multiple;
        if (is_string($options) && enum_exists($options)) {
            $options = array_map(fn ($case) => $case->label(), array_column($options::cases(), null, 'value'));
        }
        $this->options = $options;
    }

    public function isSelected($option): bool
    {
        return collect($this->value)->contains($option);
    }

    public function render()
    {
        return view('components.select');
    }
}
