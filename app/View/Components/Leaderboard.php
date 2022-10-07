<?php

namespace App\View\Components;

use App\Player;
use App\Server;
use App\Vote;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class Leaderboard extends Component
{
    protected $server;
    public function __construct(Server $server)
    {
        $this->server = $server;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        $leaders =  \Statistics::getLeaders($this->server);
        return view('components.leaderboard', ['leaders'=>$leaders]);
    }
}
