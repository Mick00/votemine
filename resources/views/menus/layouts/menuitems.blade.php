@foreach($links as $link)
    <li class="nav-item">
        @isset($link['route'])
            <a class="nav-link{{Route::currentRouteName()===$link['route']?' active':''}}"
               href="{{route($link['route'], $link['params'] ?? [])}}">
                {{$link['name']}}
            </a>
        @else
            <a class="nav-link" href="{{$link['url']}}">{{$link['name']}}</a>
        @endif
    </li>
@endforeach
