@extends("layouts.admin")

@section('main')
    <h1>{{__('Voting site')}}</h1>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">{{__('Name')}}</th>
            <th scope="col">{{__('url')}}</th>
            <th scope="col">{{__('Actions')}}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($sites as $site)
            <tr>
                <td>{{$site->name}}</td>
                <td>{{$site->url}}</td>
                <td></td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection
