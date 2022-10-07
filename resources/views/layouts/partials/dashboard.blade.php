@extends("layouts.partials.app")

@section('content')
    <div class="admin-wrapper">
        <div class="sidebar">
            @yield('sidebar')
        </div>
        <div class="view p-4">
            @yield('view')
        </div>
    </div>

@endsection
