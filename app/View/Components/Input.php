<?php

namespace App\View\Components;

class Input extends Control
{
    public function __construct($name, $id = null, $value = '', $label = '', $description = '', $bag = 'default', $type = 'text')
    {
        parent::__construct($name, $id, $value, $label, $description, $bag);
        $this->controlAttributes = $this->controlAttributes->merge(['type' => $type]);
    }

    public function render()
    {
        return view('components.input');
    }
}
