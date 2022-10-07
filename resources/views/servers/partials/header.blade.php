<div class="container-fluid p-0">
    <header class="server-header">
        <svg class="background-decoration" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="#1b1b3a" fill-opacity="1" d="M0,32L120,53.3C240,75,480,117,720,122.7C960,128,1200,96,1320,80L1440,64L1440,0L1320,0C1200,0,960,0,720,0C480,0,240,0,120,0L0,0Z"></path></svg>
        @if(Auth::user() && $server->isOwnedBy(Auth::user()))
            <div class="owner-message">
                        <span>
                            {{__('You own this server')}} <a href="{{route('server.edit')}}">{{__('edit now')}}</a>
                        </span>
            </div>
        @endif
        @include('components.picture',['image'=>$server->banner,'class'=>'header-banner'])
    </header>
    <div class="container p-0">
        <div class="server-content">
            <div class="server-meta">
                <div class="server-title">
                    @isset($server->logo)
                        @include('components.picture',['image'=>$server->logo,'class'=>'server-logo'])
                    @endisset
                    <div>
                        <h1 class="text-center">{{$server->name}}</h1>
                        <h2 class="text-center ml-2 server-ip"><span class="copiable">{{$server->ip}}{{$server->port!=25565?':'.$server->port:""}}</span></h2>
                    </div>
                </div>
                <div class="meta-list">
                    <div class="list-element">
                        {{$server->version->number}}
                    </div>
                    @foreach($server->types as $type)
                        <div class="list-element bg-success">
                            {{__($type->name)}}
                        </div>
                    @endforeach
                    @if($server->online)
                        <div class="list-element">
                            {{$server->playerCount}}/{{$server->maxPlayers}} {{__('online')}}
                        </div>
                    @else
                        <div class="list-element bg-danger">
                            {{__('Offline')}}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
