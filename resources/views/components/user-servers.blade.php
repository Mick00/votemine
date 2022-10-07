<table class="table">
    <thead>
    <tr>
        <th scope="col">{{__('Name')}}</th>
        <th scope="col">{{__('ip')}}</th>
        <th scope="col">{{__('Actions')}}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($user->servers as $server)
        <tr>
            <td><a href="{{route('server',['server_name'=>$server->name])}}">{{$server->name}}</a></td>
            <td>{{$server->ip}}</td>
            <td><a href="{{route('server.edit',['server_name'=>$server->name])}}">{{__('Edit')}}</a></td>
        </tr>
    @endforeach

    </tbody>
</table>
