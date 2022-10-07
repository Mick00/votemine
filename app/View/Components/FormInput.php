<?php

namespace App\View\Components;

use Illuminate\Http\Request;
use Illuminate\View\Component;
use PhpParser\Node\Scalar\String_;

class FormInput extends Component
{
    /**
     * Create a new component instance.
     * @param string $name
     * @return void
     */
    protected $name;
    public function __construct($name)
    {
        $this->name = "name";
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.form-input', $this->attributes);
    }
}
