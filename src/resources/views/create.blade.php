@extends(crudView('base'))

@section('content')
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-align-justify"></i> {{  trans('crud.create')  }}</div>
                        <div class="card-body">
                            <br>
                            @include('crud::partials.alert')
                            <form method="POST" action="{{$crud->route('store')}}">
                                @csrf
                                <div class="row">
                                    @foreach ($crud->getFields() as $field)
                                        @if(isset($field['permission_field']))
                                            @can($field['permission_field'])

                                            @else
                                                <?php continue; ?>
                                            @endcan
                                        @endif


                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <div class="form-group">
                                                @if($field['type'] <> 'hidden')
                                                    <strong>{!! $field['label'] !!}
                                                        : {!! crudFieldRequired($field) !!}</strong>
                                                @endif
                                                @include(crudView('fields.'.$field['type']), ['field' => $field , 'class' => 'form-control'])
                                            </div>
                                        </div>
                                    @endforeach
                                    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                        <button class="btn btn-block btn-success"
                                                type="submit">{{  trans('crud.save')  }}</button>
                                        <a href="{{ $crud->route('index') }}"
                                           class="btn btn-block btn-primary">{{ trans('crud.return') }}</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
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
@endsection


