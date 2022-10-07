@extends('layouts.home')

@section('card-body')
    <h2>{{__('Add a new server')}}</h2>
    @include('forms.add-servers')
@endsection
