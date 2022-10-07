<form method="POST" action="{{route('server.votifier.update',['name'=>$server->name])}}" enctype="multipart/form-data">
    @method('PATCH')
    @csrf
    <div class="ip-fields">
        @include('components.inputs.form-input',['name'=>'ip', 'label'=>__('IP'),'type'=>'text','required'=>'true','value'=>$server->votifierSettings->ip])
        @include('components.inputs.form-input',['name'=>'port', 'label'=>__('Port'),'type'=>'text','placeholder'=>'8192','value'=>$server->votifierSettings->port])
    </div>
    @include('components.inputs.form-input',['name'=>'token', 'label'=>__('Token'),'type'=>'text','required'=>true,'value'=>$server->votifierSettings->token])
    <button type="submit" class="btn btn-primary form-group mt-3">
        @if($server->isVotifierEnabled())
            {{ __('Update') }}
        @else
            {{__('Enable')}}
        @endif
    </button>
</form>
