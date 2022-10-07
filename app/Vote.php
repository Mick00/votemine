<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Vote
 * @package App
 * @property bool claimed
 * @property Server forServer
 * @property Player byPlayer
 * @property VoteSite onSite
 * @property \DateTime expires_at
 * @method static Builder unclaimed()
 * @method static Builder valid()
 * @method static Builder whereServer(Server $server)
 * @method static Builder wherePlayer(Player $player)
 * @method static Builder whereSite(VoteSite $site)
 */
class Vote extends Model
{
    protected $fillable = [
        'claimed', 'expires_at'
    ];

    protected $casts = [
        'claimed' => 'bool',
    ];

    protected $dates = [
        'expires_at',
    ];

    protected $hidden = [
        'id','for_server_id', 'by_player_id', 'on_site_id', 'updated_at','created_at'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function forServer(){
        return $this->belongsTo(Server::class);
    }

    public function byPlayer(){
        return $this->belongsTo(Player::class);
    }

    public function onSite(){
        return $this->belongsTo(VoteSite::class);
    }

    public function scopeUnclaimed(Builder $query): Builder{
        return $query->where('claimed',false);
    }

    public function scopeValid(Builder $query): Builder {
        return $query->where('expires_at','>',Carbon::now());
    }

    public function scopeWhereServer(Builder $query, Server $server): Builder {
        return $query->where('for_server_id', $server->id);
    }

    public function scopeWherePlayer(Builder $query, Player $player): Builder {
        return $query->where('by_player_id', $player->id);
    }

    public function scopeWhereSite(Builder $query, VoteSite $site): Builder {
        return $query->where('on_site_id', $site->id);
    }

    public function timeLeft(){
        return Carbon::now()->diff($this->expires_at);
    }

    /**
     * @throws \Exception
     */
    public function claim(){
        if ($this->claimed){
            throw new \Exception("Vote already claimed");
        }
        $this->claimed = true;
        $this->save();
    }
}
