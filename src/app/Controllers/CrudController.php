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


    public function storeMedia(Request $request)
    {
        $path = $this->crud->tmpPath;

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $file = $request->file('file');

        $name = uniqid() . '_' . trim($file->getClientOriginalName());

        $file->move($path, $name);

        return response()->json([
            'name' => $name,
            'original_name' => $file->getClientOriginalName(),
        ]);

    }


    public function getMedia($id)
    {

        $row = $this->crud->model::find($id);

        $medias = $row->media;


        $result = [];

        foreach ($medias as $media)
            $result[] = [
                "name" => $media->name,
                "size" => $media->size,
                "url" => $media->getUrl(),
                "type" => $media->type,
            ];

        return $result;


    }


    public function deleteMedia($name)
    {
        $media = Media::where('name', $name)->first();

        if (!$media)
            return false;

        Storage::delete($media->getPath());

        if (file_exists($media->getPath()))
            unlink($media->getPath());

        $media->delete();

        return true;
    }

}
