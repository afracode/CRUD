@php
    $current_value = old($field['name']) ?? $field['value'] ?? $field['default'] ?? null;
@endphp

<input type="text" class="date-persian form-control pull-right">
<input type="text" id="{{$field['name']}}" name="{{$field['name']}}" hidden>

@push('fields_scripts')
    <script src="https://unpkg.com/persian-date@1.1.0/dist/persian-date.min.js"></script>
    <script src="https://unpkg.com/persian-datepicker@1.2.0/dist/js/persian-datepicker.min.js"></script>

    <script type="text/javascript">
        let dp = $('.date-persian').persianDatepicker({
            format: 'YYYY/MM/DD',
            altField: "#{{$field['name']}}"
        });
        dp.setDate({{$current_value}});

    </script>
@endpush



