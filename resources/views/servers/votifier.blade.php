@extends('layouts.server',['server'=>$server])

@section('card-body')
    @include('servers.edit-header',['server'=>$server])
    <h3>{{__('Votifier')}}</h3>
    @include('components.on-success-alert')
    @include('forms.edit-votifier-settings')
@endsection
