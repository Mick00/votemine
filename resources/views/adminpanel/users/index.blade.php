@extends("layouts.admin")

@section('main')
    <h1>Users</h1>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">{{__('Name')}}</th>
            <th scope="col">{{__('Email')}}</th>
            <th scope="col">{{__('Actions')}}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr>
                <td>{{$user->name}}</td>
                <td>{{$user->email}}</td>
                <td></td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection
