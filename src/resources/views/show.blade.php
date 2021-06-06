@extends(crudView('base'))

@section('content')
    <div class="container-fluid">
        <div class="fade-in">
            <div class="card">
                <div class="card-header">{{trans('crud.show')}}</div>
                <div class="card-body">
                    <table class="table">
                        <tbody>

                        @foreach ($crud->fields as $field)

                            @php $f = $crud->getFieldValue($field['name'])  @endphp


                            <tr>
                                <td>
                                    <p>{!! crudShowLabel($field) !!}</p>
                                </td>

                                @if(isset($f['type']) && $f['type'] == 'image')
                                    <td>
                                        <img src="{{$f['value']}}" alt="{{$f['label']}}" width="200" height="200">
                                    </td>
                                @else
                                    <td>
                                        @include('crud::show_fields/'.$field['type'])
                                    </td>
                                @endif
                            </tr>





                        @endforeach

                        <tr>
                            <td>
                                <a href="{{$crud->route('index' )}}"
                                   class="btn btn-success">{{trans('crud.buttons.index')}}</a>
                                @can($crud->permission('update'))
                                    <a href="{{$crud->route('edit' , $crud->row->id)}}"
                                       class="btn btn-primary">{{trans('crud.buttons.edit')}}</a>
                                @endcan
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @stack('fields_scripts')
@endsection

@section('style')
    @stack('fields_css')

    <style>
        tr {

        }
    </style>
@endsection


