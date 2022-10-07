@extends('layouts.partials.app')

@section('content')
<div class="login-container">
    <div class="card shadow my-5">
        <div class="login-header-wrapper">
            <div class="login-header shadow"><h1>{{ __('Login') }}</h1></div>
        </div>
        <div class="card-body">
            @include('forms.login')
        </div>
    </div>
</div>
@endsection
