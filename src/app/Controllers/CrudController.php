<?php


namespace Afracode\CRUD\App\Controllers;


use Afracode\CRUD\app\Classes\Crud;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class CrudController extends Controller
{

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

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
        return view(crudView('datatable'), ['crud' => $this->crud]);
    }


    public function create()
    {
        $this->crud->resetFields();
        $this->setupCreate();

        return view(crudView('create'),
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


        return view(crudView('edit'),
            [
                'crud' => $this->crud
            ]
        );
    }


    public function storeMedia1(Request $request)
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


    public function test()
    {

    }

    public function dataTable()
    {
        $this->setupIndex();

        $datatable = \Yajra\DataTables\DataTables::of($this->crud->query)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return view('crud::partials.actions', ['id' => $row->id, 'crud' => $this->crud]);
            })->rawColumns(['action']);


        foreach ($this->crud->columns as $column) {
            if ($column['change_to'])
                $datatable = $datatable->editColumn($column['data'], $column['change_to']);
        }


        return $datatable->make(true);

    }


    public function update(Request $request, $id)
    {
        $this->crud->setRow($id);
        $this->setupEdit();


        $this->validate($request, array_merge($this->crud->getValidations()));

        $input = $request->only($this->crud->getFields('name'));

        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = Arr::except($input, array('password'));

        }

        $this->crud->row->update($input);
        DB::table('model_has_roles')->where('model_id', $id)->delete();
        DB::table('model_has_permissions')->where('model_id', $id)->delete();


        if ($this->crud->hasTrait('HasRoles')) {
            $this->crud->row->assignRole($request->input('roles'));
            $this->crud->row->givePermissionTo($request->input('permissions'));
        }


        $input = $request->all();
        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = $request->except(['password']);
        }


        if ($this->crud->hasTrait('InteractsWithMedia')) {
            $media = $this->crud->row->getMedia('*')->pluck('file_name')->toArray();

            foreach ($request->input('mediable', []) as $file) {
                if (count($media) === 0 || !in_array($file, $media)) {
                    $this->crud->row->addMedia(storage_path('tmp/' . $file))->toMediaCollection();
                }
            }
        }


        return redirect()->back()->with('success', trans('message.updated'));
    }


    public function store(Request $request)
    {

        $path = $this->crud->tmpPath;

        $this->setupCreate();

        $this->validate($request, $this->crud->getValidations());

        $fields =  $fields = $this->crud->getFormInputs($request);;

        $new = $this->crud->model::create($fields);


        if ($this->crud->hasTrait('HasRoles')) {
            $this->crud->row->assignRole($request->input('roles'));
            $this->crud->row->givePermissionTo($request->input('permissions'));
        }


        foreach ($request->input('mediable', []) as $file) {

            $new->addMedia(storage_path($path . $file))->toMediaCollection();
        }

        return redirect($this->crud->route('index'));
    }


    public function destroy($id)
    {
        $row = $this->crud->model::find($id);

        if ($this->crud->hasTrait('InteractsWithMedia')) {
            foreach ($row->getMedia('*') as $media)
                $media->delete();
        }

        $row->delete();

        return true;
    }
}
