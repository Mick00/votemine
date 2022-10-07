@php $id = "input-{$name}-".rand(0,1000) @endphp
<div class="form-group">
    <label for="{{$id}}">{{$label}}</label>
    <select class="custom-select" name="{{$name}}" id="{{$id}}"
        @isset($disabled)disabled="{{$disabled}}"@endisset
        @isset($required) required="{{$required}}" @endisset
        @isset($multiple) multiple="{{$multiple}}"@endisset
    >
        @foreach($options as $option)
            <option value="{{$option['value']}}"
                @isset($selected){{$selected == $option['value']?'selected="selected"':""}}@endif
            >{{$option['label']}}</option>
        @endforeach
    </select>
    @error($name)
    <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>
