<form method="POST" action="{{ route('login') }}">
@csrf
@include('components.inputs.form-input',['name'=>'email', 'label'=>__('E-mail address'),'type'=>'email','required'=>true,'value'=>old('email'),'autocomplete'=>'email'])
@include('components.inputs.form-input',['name'=>'password', 'label'=>__('Password'),'type'=>'password','required'=>true,'autocomplete'=>'current-password'])
<div class="form-group form-check">
    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
    <label class="form-check-label" for="remember">
        {{ __('Remember Me') }}
    </label>
</div>
<div class="form-group">
    <button type="submit" class="btn btn-primary btn-lg">
        {{ __('Login') }}
    </button>

    @if (Route::has('password.request'))
        <a class="btn btn-link" href="{{ route('password.request') }}">
            {{ __('Forgot Your Password?') }}
        </a>
    @endif
</div>
</form>
