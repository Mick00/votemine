@extends("layouts.partials.dashboard")

@section('sidebar')
    @include('menus.admin', ['style'=>'bg-dark'])
@endsection

@section('view')
    @yield('main')
@endsection
