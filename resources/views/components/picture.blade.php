<picture>
    @foreach($image->getPictures() as $imageSrc)
        <source srcset="{{$imageSrc['src']}}"
                type="image/{{$imageSrc['format']}}"
                media="(min-width: {{$imageSrc['minWidth']}}px)">
    @endforeach
        <img src="{{$image->url()}}" alt="picture"
        @isset($class) class="{{$class}}" @endisset
        >
</picture>
