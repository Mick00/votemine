@php $id = "input-{$name}-".rand(0,1000) @endphp
<div class="form-group">
    @if(!empty($label))
    <p class="label">{{$label}}</p>
    @endif
    <div class="custom-file">
        <input type="file" name="{{$name}}" class="custom-file-input" id="{{$id}}" lang="{{App::getLocale()}}">
        <label class="custom-file-label" for="{{$id}}">{{__('Choose a file')}}</label>
    </div>
    <script type="text/javascript">
        document.querySelectorAll('.custom-file input').forEach( input => {
            input.addEventListener('change', function (e) {
                let files = [];
                for (let i = 0; i < e.target.files.length; i++) {
                    files.push(e.target.files[i].name);
                }
                $(this).next('.custom-file-label').html(files.join(', '));
            });
        });
    </script>
</div>

