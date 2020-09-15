<?php

namespace Afracode\CRUD\App\View\Components;


use App\Helpers\Assoc;
use Illuminate\View\Component;

class Menu extends Component
{
    public $items;
    public $view;


    public function __construct($group = 'main', $view)
    {

        $this->items = \Afracode\CRUD\App\Models\Menu::where('group', $group)->get();

        $this->view = $view;
    }


    public function render()
    {
        return view('components.' . $this->view);
    }


}


