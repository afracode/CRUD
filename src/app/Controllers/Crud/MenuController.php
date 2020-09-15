<?php


namespace Afracode\CRUD\app\Controller\Crud;


use Afracode\CRUD\App\Classes\Crud;
use Afracode\CRUD\App\Models\Menu;

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
