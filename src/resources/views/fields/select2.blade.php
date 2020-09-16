@php
    $current_value = old($field['name']) ?? $field['value'] ?? $field['default'] ?? '';
    $options = $field['options'];

@endphp


<select
    name="{{ $field['name'] }}"
    class="{{$class}} select2-basic-single"
>
    <option selected>{{trans('crud.please_select')}}</option>
    @if (count($options))
        @foreach ($options as $key => $value)
            @if($current_value == $key)
                <option value="{{ $key }}" selected>{{ $value}}</option>
            @else
                <option value="{{ $key }}">{{ $value}}</option>
            @endif
        @endforeach
    @endif
</select>


@if ($crud->notLoaded($field))
    @push('fields_scripts')
        <script>
            $(document).ready(function () {
                $('.select2-basic-single').select2();
            });
        </script>
    @endpush
@endif



