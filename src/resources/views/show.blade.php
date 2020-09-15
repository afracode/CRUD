@extends(crudView('base'))

@section('content')
    <div class="container-fluid">
        <div class="fade-in">
            <div class="card">
                <div class="card-header">{{trans('crud.show')}}</div>
                <div class="card-body">
                    <table class="table">
                        <tbody>

                        @foreach ($crud->getFields() as $field)
                            <tr>
                                <td>
                                    <p>{!! crudShowLabel($field) !!}</p>
                                </td>
                                <td><span>{{$field['value']}}</span></td>
                            </tr>
                        @endforeach
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


