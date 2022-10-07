<div>
    @foreach($server->tokens as $token)
        <div>{{$token->name}}
            <form action="{{route('server.token')}}" method="POST">
                @csrf
                @method('DELETE')
                @include('components.inputs.hidden-input',['type'=>'text','name'=>'key_name','value'=>$token->name])
                <button class="btn btn-primary">{{__('Delete')}}</button>
            </form></div>
    @endforeach
</div>
<form action="{{route('server.token', ['name'=>$server->name])}}" method="POST">
    @csrf
    @include('components.inputs.form-input',['type'=>'text','name'=>'key_name','label'=>__('Api key name')])
    <button class="btn btn-primary">{{__('Generate new key')}}</button>
</form>
