<?php


namespace App;


use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ServerStatistics
{
    public function getLeaders(Server $server, $n = 10){
        $voteQuery = Vote::whereServer($server)
            ->select(['by_player_id',DB::raw('count(*) as total')])
            ->whereDate('created_at','>=', Carbon::now()->startOfMonth()->toDateString())
            ->whereDate('created_at','<=', Carbon::now()->endOfMonth()->toDateString())
            ->groupBy('by_player_id');

        return Player::joinSub($voteQuery,'valid_votes', function ($join){
            $join->on('players.id','=','valid_votes.by_player_id');
        })->select(['name',DB::raw('sum(total) as total')])
            ->groupBy('name')
            ->orderBy('total', 'desc')
            ->limit($n)
            ->get();
    }

    public function votesToday(Server $server){
        return $this->votesBetween($server, Carbon::now()->floorDay(), Carbon::now()->ceilDay());
    }

    public function votesThisWeek(Server $server){
        return $this->votesBetween($server,Carbon::now()->subDays(7), Carbon::now());
    }

    public function votesBetween(Server $server, Carbon $start, Carbon $end){
        return Vote::whereServer($server)
            ->whereDate('created_at','>=', $start->toDateString())
            ->whereDate('created_at','<', $end->toDateString())
            ->count();
    }
}
