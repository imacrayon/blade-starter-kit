<?php

namespace App\View\Components;

class Checkbox extends Control
{
    public $checked;

    public function __construct($name, $id = null, $value = '1', $label = '', $description = '', $bag = 'default', $checked = false)
    {
        parent::__construct($name, $id, $value, $label, $description, $bag);
        $this->value = $value;
        $this->controlAttributes = $this->controlAttributes->merge(['checked' => (bool) $checked]);
    }

    public function render()
    {
        return view('components.checkbox');
    }
}
