<?php


namespace Afracode\CRUD\App\Controllers;


use Afracode\CRUD\app\Classes\Crud;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class CrudController extends Controller
{

    public $crud;


    public function __construct()
    {
        $this->crud = new Crud();
        $this->config();
    }


    public function config()
    {

    }


    public function setupIndex()
    {

    }


    public function setupCreate()
    {

    }


    public function setupEdit()
    {

    }


    public function index()
    {
        $this->setupIndex();
        return view('crud::dashboard.datatable', ['crud' => $this->crud]);
    }


    public function create()
    {
        $this->crud->resetFields();
        $this->setupCreate();

        return view('crud::dashboard.create',
            [
                'crud' => $this->crud
            ]
        );
    }


    public function edit($id)
    {
        $this->crud->resetFields();
        $this->crud->setRow($id);
        $this->setupEdit();
        $this->crud->setDefaults();


        return view('crud::dashboard.edit',
            [
                'crud' => $this->crud
            ]
        );
    }



}
