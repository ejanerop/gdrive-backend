<?php

namespace App\View\Components;

use Illuminate\View\Component;

class File extends Component
{
    /**
     * The alert type.
     *
     * @var any
     */
    public $file;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.file');
    }
}
