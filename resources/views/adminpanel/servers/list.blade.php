@extends("layouts.admin")

@section('main')
    <h1>Servers</h1>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">{{__('Name')}}</th>
            <th scope="col">{{__('# vote site')}}</th>
            <th scope="col">{{__('Owner')}}</th>
            <th scope="col">{{__('Actions')}}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($servers as $server)
            <tr>
                <td>{{$server->name}}</td>
                <td>{{$server->voteSites->count()}}</td>
                <td>{{$server->ownedBy->name}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection
