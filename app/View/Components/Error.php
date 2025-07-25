<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Error extends Component
{
    public $id;

    public $for;

    public $value;

    public $bag;

    public function __construct($for, $value = null, $bag = 'default')
    {
        $this->id = $for.'_error';
        $this->for = Control::sessionPath($for);
        $this->value = $value;
        $this->bag = $bag;
    }

    public function render()
    {
        return view('components.error');
    }
}
