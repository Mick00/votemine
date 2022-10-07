@php $id = "input-{$name}-".rand(0,10000) @endphp
<div class="form-check">
    <input name="{{$name}}" class="form-check-input" type="checkbox" value="{{$value}}" id="{{$id}}"
           @istrue($checked) checked @endistrue
           @isset($disabled)disabled="{{$disabled}}"@endisset>
    <label class="form-check-label" for="{{$id}}">
        {{$label}}
    </label>
</div>
