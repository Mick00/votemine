<div class="alert-wrapper">
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block mt-3 mb-0">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
        <br>
    @endif
</div>
