@extends('layouts.page')
@php $server->updateIfNeeded() @endphp
@section('page-content')
    <div class="server-container">
        @include('servers.partials.header',['server'=>$server])
        <div class="info-container">
            <div class="container px-5 pb-4">
                <p class="mt-5">{{$server->description}}</p>
                <div class="d-flex justify-content-center align-items-center">
                    @if($server->website_url)
                        <a class="btn btn-info btn-lg" href="{{$server->website_url}}" target="_blank">{{__('know more')}}</a>
                    @endif
                </div>
            </div>
            <div class="votes-section">
                <div class="container">
                    <section class="server-sites">
                        <div class="text-center w-100 pb-3">
                            <h2 class="bg-primary text-light rounded p-2 px-4 d-inline-block">{{__('Vote for this site')}}</h2>
                            <a class="text-light h5 mt-3 ml-3 stats-link" href="{{route('server.statistic')}}">{{__('Statistics')}}<i class="ml-1 fas fa-chevron-right"></i></a>
                        </div>
                        @include('components.server-voting-site-list',['server'=>$server])
                        <div class="site-validation pt-5">
                            <h3 class="mr-sm-3">{{__('Validate your votes')}}</h3>
                            @include('forms.validate-vote', ['server'=>$server])
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
@endsection
