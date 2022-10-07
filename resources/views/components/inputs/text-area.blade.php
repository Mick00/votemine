@php $id = "input-{$name}-".rand(0,1000) @endphp

<div>
    <div class="form-group">
        <label for="{{$id}}" class="form-label">{{ __($label) }}</label>
        @error($name)
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <textarea id="{{$id}}"
               class="form-control @error($name) is-invalid @enderror"
               name="{{$name}}"
               @isset($placeholder) placeholder="{{$placeholder}}" @endisset
               @isset($required) required="{{$required}}" @endisset
               @isset($autofocus) autofocus="{{$autofocus}}" @endif>
            {{ $value ?? old($name) }}
        </textarea>

    </div>
</div>
