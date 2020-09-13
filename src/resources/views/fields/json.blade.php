@if($field['value'])
<div class="row" style="margin-top: 16px; margin-bottom: -20px">
    <div class="form-group col-sm-3">
        <label><strong>زبان:</strong></label>
    </div>
    <div class="form-group col-sm-9">
        <label><strong>ترجمه:</strong></label>
    </div>
</div>
    @foreach($field['value'] as $key => $value)
        <div class="row">
            <div class="form-group col-sm-3">
                <input class="form-control" type="text" value="{{$key}}">
            </div>
            <div class="form-group col-sm-9">
                <input class="form-control" name="{{$field['name']}}[{{$key}}]" type="text" value="{{$value}}">
            </div>
        </div>
    @endforeach
@endif
