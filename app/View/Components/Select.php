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
        $this->options = $options;
    }

    public function isSelected($option): bool
    {
        if (is_array($this->value)) {
            return in_array($option, $this->value);
        }

        return (string) $option === (string) $this->value;
    }

    public function render()
    {
        return view('components.select');
    }
}
