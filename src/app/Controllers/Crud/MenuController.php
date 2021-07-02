<?php


namespace Afranext\Crud\app\Controller\Crud;


use Afranext\Crud\App\Classes\Crud;
use Afranext\Crud\App\Models\Menu;

class MenuController extends Crud
{
    public function config()
    {
        $this->crud->setModel(Menu::class);
        $this->crud->SetEntity('menu');
    }


    public function setupIndex()
    {
        $this->crud->setColumn('href');
        $this->crud->setColumn('icon');
        $this->crud->setColumn('permission');
        $this->crud->setColumn('action');
    }


    public function setupCreate()
    {
        $this->crud->setField([
            'name' => 'href'
        ]);

        $this->crud->setField([
            'name' => 'icon'
        ]);

        $this->crud->setField([
            'name' => 'permission'
        ]);
    }
}
