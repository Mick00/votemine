@foreach($server->voteSites as $votingSite)
    <form action="{{route('server.site.update',['name'=>$server->name])}}" method="POST">
        @csrf
        @method('PATCH')
        <div class="d-flex justify-content-around">
            <div class="d-flex">
                <span class="font-weight-bold align-self-center">{{$votingSite->name}}</span>
            </div>
            @include('components.inputs.hidden-input',['name'=>'site','value'=>$votingSite->id])
            @include('components.inputs.form-input',[
                'name'=>'id',
                'type'=>'text',
                'label'=>__('Server\'s id on the voting site'),
                'value'=>$votingSite->getServerId($server)])
            <button type="submit" class="btn btn-primary align-self-end form-group">
                {{ __('Update') }}
            </button>
        </div>
    </form>
@endforeach
