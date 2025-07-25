<?php

namespace App\View\Components;

class Radio extends Control
{
    public $checked;

    public function __construct($name, $id = null, $value = '', $label = '', $description = '', $bag = 'default')
    {
        parent::__construct($name, $id, $value, $label, $description, $bag);
        $this->value = $value;
    }

    public function render()
    {
        return view('components.radio');
    }
}
