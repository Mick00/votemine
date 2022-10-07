@extends('layouts.page')
@section('page-content')
    <div class="server-container">
        @include('servers.partials.header',['server'=>$server])
        <div class="container mt-5 statistics-container">
            <a href="{{route('server')}}"><i class="fas fa-chevron-left mr-2"></i>{{__('Back')}}</a>
            <h2>{{__('Statistics')}}</h2>
            <div class="statistic-summary">
                <div class="ml-2 bg-primary rounded">
                    <span>{{Statistics::votesToday($server)}}</span>
                    <p>{{__('Votes today')}}</p>
                </div>
                <div class="mr-2 bg-secondary rounded">
                    <span>{{Statistics::votesThisWeek($server)}}</span>
                    <p>{{__('Votes in the last 7 days')}}</p>
                </div>
            </div>
            <div class="leaderboard-wrapper mt-4 table-hover">
                <h2>{{__('Top voters')}}</h2>
                <x-leaderboard :server="$server"/>
            </div>
        </div>
    </div>
@endsection
