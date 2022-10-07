@extends('layouts.server',['server'=>$server])

@section('card-body')
    @include('servers.edit-header',['server'=>$server])
    <h3 class="mb-3">{{__('Api keys')}}</h3>
    @include('components.apikey-list',['server'=>$server])
@endsection
