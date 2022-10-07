@extends('layouts.server',['server'=>$server])

@section('card-body')
    @if($server->isOwnedBy(Auth::user()))
        @include('servers.edit-header',['server'=>$server])
        <h3>{{__('Server info')}}</h3>
        @include('forms.edit-servers',['server'=>$server])
    @else
        <div>{{__('You do not have permission to edit this server')}}</div>
    @endif
@endsection
