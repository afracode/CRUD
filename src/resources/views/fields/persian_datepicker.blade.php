@php
    $current_value = old($field['name']) ?? $field['value'] ?? $field['default'] ?? null;


@endphp

<input type="text" id="{{$field['name']}}" class="date-persian form-control pull-right">
<input type="text" id="alt_{{$field['name']}}" name="{{$field['name']}}" hidden>




@push('fields_scripts')

    @if ($crud->notLoaded($field))
        <script src="https://unpkg.com/persian-date@1.1.0/dist/persian-date.min.js"></script>
        <script src="https://unpkg.com/persian-datepicker@1.2.0/dist/js/persian-datepicker.min.js"></script>
    @endif

    <script type="text/javascript">
        $("#{{$field['name']}}").persianDatepicker({
            format: 'YYYY/MM/DD',
            altField: "#alt_{{$field['name']}}"
        }).setDate({{ $current_value }});

    </script>
@endpush


