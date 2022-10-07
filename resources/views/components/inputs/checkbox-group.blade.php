@isset($label)<label>{{$label}}</label>@endisset
<div class="checkbox-group">
@foreach($checkboxes as $checkbox)
    @include('components.inputs.checkbox', $checkbox)
@endforeach
</div>
