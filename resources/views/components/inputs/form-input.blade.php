@php $id = "input-{$name}-".rand(0,1000) @endphp

<div class="form-group {{$class??""}}">
    <label for="{{$id}}" class="form-label">{{ __($label) }}</label>
        <input id="{{$id}}" type="{{$type}}"
               class="form-control @error($name) is-invalid @enderror"
               name="{{$name}}" value="{{ $value ?? old($name) }}"
               @isset($placeholder) placeholder="{{$placeholder}}" @endisset
               @isset($required) required="{{$required}}" @endisset
               @isset($autocomplete) autocomplete="{{$autocomplete}}" @endisset
               @isset($autofocus) autofocus="{{$autofocus}}" @endif>
        @error($name)
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
</div>
