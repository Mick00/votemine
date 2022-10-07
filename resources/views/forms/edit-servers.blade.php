@include('components.on-success-alert')
<form method="POST" action="{{route('server.update',['name'=>$server->name])}}" enctype="multipart/form-data">
    @csrf
    @method('PATCH')
    @include('components.inputs.form-input',['name'=>'name', 'label'=>__('Name'),'type'=>'text','required'=>'true','value'=>$server->name])
    @include('components.inputs.select',['name'=>'version_id', 'label'=>__('Version'),'required'=>'true','options'=>\App\Version::toSelectOptions(),'selected'=>$server->version->id])
    @include('components.inputs.checkbox-group',['label'=>__('Type'),'checkboxes'=>\App\Type::toCheckboxes($server)])
    @include('components.inputs.form-input',['name'=>'website_url', 'label'=>__('Website'),'type'=>'text','value'=>$server->website_url])
    <div class="ip-fields">
        @include('components.inputs.form-input',['name'=>'ip', 'label'=>__('IP'),'type'=>'text','required'=>'true', 'value'=>$server->ip])
        @include('components.inputs.form-input',['name'=>'port', 'label'=>__('Port'),'type'=>'text', 'placeholder'=>'25565','value'=>$server->port])
    </div>
    @include('components.inputs.text-area',['name'=>'description', 'label'=>__('Description'),'value'=>$server->description])
    @if($server->banner)
        <div class="w-100 text-center">
            <img src="{{$server->banner->url()}}" alt="{{$server->name}} banner" class="mw-100">
        </div>
    @else
        <div>{{__('Currently no banner')}}</div>
    @endif
    @include('components.inputs.file',['name'=>"banner", 'label'=>__('Banner')])
    @if($server->logo)
        <div class="w-100 text-center">
            <img src="{{$server->logo->url()}}" alt="{{$server->name}} logo" height="100px">
        </div>
    @else
        <div>{{__('Currently no logo')}}</div>
    @endif
    @include('components.inputs.file',['name'=>"logo", 'label'=>__('Logo')])
    <button type="submit" class="btn btn-primary">
        {{ __('Update') }}
    </button>
</form>
