<?php

namespace Litstack\Bladesmith\Components;

use Illuminate\View\Component;

class OffCanvasComponent extends Component
{
    /**
     * Direction from where the off canvas animates in.
     *
     * @var string
     */
    public $direction;

    /**
     * Custom css class(es).
     *
     * @var string
     */
    public $class;

    /**
     * Custom id attribute.
     *
     * @var string
     */
    public $id;

    /**
     * Create new OffCanvas instance.
     *
     * @param  string $direction
     * @param  string $class
     * @param  string $id
     * @return void
     */
    public function __construct($direction = 'rtl', $class = '', $id = 'lit-burger-target')
    {
        $this->direction = $direction;
        $this->class = $class;
        $this->id = $id;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('bladesmith::off-canvas');
    }
}
