@if($crud->hasAction('read'))
    <a href="{{$crud->route('show' , $id)}}" class="btn btn-success">{{trans('crud.view')}}</a>
@endif

@if($crud->hasAction('edit'))
    @can($crud->permission('update'))
        <a href="{{$crud->route('edit' , $id)}}" class="btn btn-primary">{{trans('crud.edit')}}</a>
    @endcan
@endif

@if($crud->hasAction('delete'))
    @can($crud->permission('delete'))
        <button data-remote="{{$crud->route('delete' , $id)}}" class="btn btn-danger ">{{trans('crud.delete')}}</button>
    @endcan
@endif




