@extends('layouts.server',['server'=>$server])

@section('card-body')
        @include('servers.edit-header',['server'=>$server])
        <h3>{{__('Voting sites')}}</h3>
        @include('components.on-success-alert')
        @include('forms.edit-server-vote-site')
        <h4>{{__('Add a site')}}</h4>
        @include('forms.add-voting-site')

@endsection
