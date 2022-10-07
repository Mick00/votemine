@if ($message = Session::get('success'))
    <div class="alert alert-success alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{{ $message }}</strong>
    </div>
    <br>
@endif
<form method="POST" action="{{route('home.create.server')}}" enctype="multipart/form-data">
    @csrf
    @include('components.inputs.form-input',['name'=>'name', 'label'=>__('Name'),'type'=>'text','required'=>'true'])
    @include('components.inputs.select',['name'=>'version_id', 'label'=>__('Version'),'required'=>'true','options'=>\App\Version::toSelectOptions()])
    @include('components.inputs.checkbox-group',['label'=>__('Type'),'checkboxes'=>\App\Type::toCheckboxes()])
    @include('components.inputs.form-input',['name'=>'website_url', 'label'=>__('Website'),'type'=>'text'])
    <div class="ip-fields">
        @include('components.inputs.form-input',['name'=>'ip', 'label'=>__('IP'),'type'=>'text','required'=>'true'])
        @include('components.inputs.form-input',['name'=>'port', 'label'=>__('Port'),'type'=>'text', 'placeholder'=>'25565'])
    </div>
    @include('components.inputs.text-area',['name'=>'description', 'label'=>__('Description')])
    @include('components.inputs.file',['name'=>"banner", 'label'=>__('Banner')])
    @include('components.inputs.file',['name'=>"logo", 'label'=>__('Logo')])
    <button type="submit" class="btn btn-primary form-group mt-3">
        {{ __('Add') }}
    </button>
</form>
