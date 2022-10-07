@extends('layouts.home')

@section('card-body')
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif
    <h1>{{__('Welcome :name', ['name'=>Auth::user()->name])}}</h1>
    <div class="d-flex justify-content-between mb-2">
        <h2>{{__('Your servers')}}</h2>
        <a class="btn btn-primary align-self-center" href="{{route('home.addserver')}}">{{__('Add a server')}}</a>
    </div>
    <x-user-servers :user="Auth::user()"/>
@endsection
