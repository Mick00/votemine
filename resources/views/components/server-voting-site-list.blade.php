<div class="sites-list-wrapper">
    @foreach($server->voteSites as $votingSite)
        <div class="site-card shadow px-4 pt-4 bg-light">
            <div class="img-wrapper">
                <picture>
                    <source srcset="{{Storage::url($votingSite->logo_public_path.".w300-q85.webp")}}" type="image/webp">
                    <source srcset="{{Storage::url($votingSite->logo_public_path.".w300-q85.png")}}" type="image/png"/>
                    <img src="{{Storage::url($votingSite->logo_public_path)}}" alt="{{$votingSite->name}} logo">
                </picture>
            </div>
            <div class="site-text">
                @php $vote = App\VoteValidation\VoteHandler::lastVote($server, $votingSite,"request()->ip()", request()->cookie('player_name')) @endphp

                <span>{{$votingSite->name}}</span>
                <div>
                @if($vote != null)
                    <a href="{{$votingSite->getServerUrlOnSite($server)}}"
                       target="_blank"
                       class="btn btn-success">
                        {{__('Voted')}}
                        <span>
                        <i class="fa fa-clock-o"></i>
                        @if($vote->timeLeft()->h > 0)
                            {{$vote->timeLeft()->h}}h
                        @elseif($vote->timeLeft()->m > 0)
                            {{$vote->timeLeft()->i}}m
                        @else
                            {{$vote->timeLeft()->s}}s
                        @endif
                        </span>
                    </a>
                @else
                    <a href="{{$votingSite->getServerUrlOnSite($server)}}"
                       target="_blank"
                       class="btn btn-primary">
                        {{__('Vote')}} <i class="fas fa-check ml-3"></i>
                    </a>
                @endif
                </div>
            </div>
        </div>
    @endforeach
</div>

