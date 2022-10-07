<table class="table">
    <thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col">{{__('Name')}}</th>
        <th scope="col">{{__('Total votes')}}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($leaders as $leader)
        <tr>
            <th scope="row">{{$loop->index+1}}.</th>
            <td>{{$leader->name}}</td>
            <td>{{$leader->total}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
