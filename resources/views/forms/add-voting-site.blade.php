<form method="POST" action="{{route('server.site.add',['name'=>$server->name])}}">
    @csrf
    <div class="d-flex justify-content-around">
        <div class="form-group">
            <label for="site">{{__('Select website')}}</label>
            <select name="site" id="site" class="custom-select form-control">
                @foreach(\App\VoteSite::all()->diff($server->voteSites) as $votingSite)
                    <option value="{{$votingSite->id}}">{{$votingSite->name}}</option>
                @endforeach
            </select>
        </div>
        @include('components.inputs.form-input',['name'=>'id', 'type'=>'text','label'=>__('Server\'s id on the voting site'), 'class'=>''])
        <button type="submit" class="btn btn-primary form-group align-self-end">
            {{ __('Add') }}
        </button>
    </div>
</form>
