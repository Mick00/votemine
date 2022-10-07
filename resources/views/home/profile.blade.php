@extends('layouts.home')

@section('card-body')
    @include('components.profile',['user'=>Auth::user()])
@endsection
