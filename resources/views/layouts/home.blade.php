@extends('layouts.partials.app')

@section('content')
    <main class="bg-light">
        <div class="container py-4">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow">
                        <div class="card-header bg-white pb-0">
                            @include('menus.home')
                        </div>
                        <div class="card-body">
                            @if(Auth::check())
                                @yield('card-body')
                            @else
                                <div class="alert aler-danger">{{__('You should be logged in to view this')}}</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
