@extends(crudView('base'))

@section('content')
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-align-justify"></i>{{ trans('crud.list') }}</div>
                        <div class="card-body">

                            @if($crud->hasAction('create'))
                                @can($crud->permission('delete'))
                                    <a href="{{$crud->route('create')}}" class="btn btn-primary m-2"
                                       style="margin-bottom: 30px !important; ">
                                        {{ trans('crud.create')  }}
                                    </a>
                                @endcan
                            @endif


                            <table class="table table-bordered data-table" id="laravel_datatable">
                                <thead>
                                <tr>

                                    @foreach($crud->columns as $column)

                                        <th>{{$column['name']}}</th>
                                    @endforeach
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection



@section('script')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script>


        function getLang(lang) {

            switch (lang) {
                case 'en':
                    return {
                        "decimal": "",
                        "emptyTable": "No data available in table",
                        "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                        "infoEmpty": "Showing 0 to 0 of 0 entries",
                        "infoFiltered": "(filtered from _MAX_ total entries)",
                        "infoPostFix": "",
                        "thousands": ",",
                        "lengthMenu": "Show _MENU_ entries",
                        "loadingRecords": "Loading...",
                        "processing": "Processing...",
                        "search": "Search:",
                        "zeroRecords": "No matching records found",
                        "paginate": {
                            "first": "First",
                            "last": "Last",
                            "next": "Next",
                            "previous": "Previous"
                        },
                        "aria": {
                            "sortAscending": ": activate to sort column ascending",
                            "sortDescending": ": activate to sort column descending"
                        }
                    }
                    break;
                case'fa':
                    return {
                        "sEmptyTable": "هیچ داده‌ای در جدول وجود ندارد",
                        "sInfo": "نمایش _START_ تا _END_ از _TOTAL_ ردیف",
                        "sInfoEmpty": "نمایش 0 تا 0 از 0 ردیف",
                        "sInfoFiltered": "(فیلتر شده از _MAX_ ردیف)",
                        "sInfoPostFix": "",
                        "sInfoThousands": ",",
                        "sLengthMenu": "نمایش _MENU_ ردیف",
                        "sLoadingRecords": "در حال بارگزاری...",
                        "sProcessing": "در حال پردازش...",
                        "sSearch": "جستجو:",
                        "sZeroRecords": "رکوردی با این مشخصات پیدا نشد",
                        "oPaginate": {
                            "sFirst": "برگه‌ی نخست",
                            "sLast": "برگه‌ی آخر",
                            "sNext": "بعدی",
                            "sPrevious": "قبلی"
                        },
                        "oAria": {
                            "sSortAscending": ": فعال سازی نمایش به صورت صعودی",
                            "sSortDescending": ": فعال سازی نمایش به صورت نزولی"
                        }
                    }
                    break;
                default:
            }

        }



        $(document).ready(function () {
            $('#laravel_datatable').DataTable({
                processing: true,
                serverSide: true,
                "language": getLang("{{App::getLocale()}}"),
                ajax: {
                    url: "{{$crud->route('datatable')}}",
                    method: "POST"
                    ,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                },
                columns: {!! $crud->getDatatableColumns() !!}
            });
        });
    </script>


    <script>
        $('#laravel_datatable').on('click', '.btn-danger[data-remote]', function (e) {
            var choice = confirm('آیا مطمئن هستید؟');
            if (choice !== true) {
                return false;
            }
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var url = $(this).data('remote');
            // confirm then
            $.ajax({
                url: url,
                type: 'DELETE',
                dataType: 'json',
                data: {method: '_DELETE', submit: true}
            }).always(function (data) {
                $('#laravel_datatable').DataTable().draw(false);
            });
        });
    </script>

@endsection


@section('style')
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
@endsection

