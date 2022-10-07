<form method="POST" action="{{route('server.validate')}}" class="site-validation-form">
    @csrf
        @include('components.inputs.form-input',['name'=>'playername', 'label'=>__('Player name'),'type'=>'text','required'=>true,'value'=>request()->cookie('player_name')])
        <button type="submit" class="btn btn-primary form-group ml-3">
            {{ __('Validate') }}
        </button>
</form>
