<?php

namespace App;

use App\VoteValidation\PlayerVoteValidator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Class VoteSite
 * @package App
 * @property string url
 * @property string name
 * @property string logo_url
 * @property string server_url
 * @property int vote_lifespan
 * @property PlayerVoteValidator validator
 */
class VoteSite extends Model
{
    private $SERVER_ID_PLACEHOLDER = "{id}";

    protected $fillable = [
        'url',
        'name',
        'logo_public_path',
        'server_url',
        'vote_lifespan',
        'validator'
    ];

    public function servers(){
        return $this->belongsToMany(Server::class);
    }

    public function getValidatorAttribute($validatorClass){
        return new $validatorClass($this);
    }

    public function addRegisteredServer(Server $server, $idOnSite){
        $this->servers()->attach($server);
        $this->save();
        $this->defineServerId($server, $idOnSite);
    }

    /**
     * Sets the id of the server on the foreign site
     * @param Server $server
     * @param $id
     */
    public function defineServerId(Server $server, $id): void{
        $this->getServerMetaDBRow($server)
            ->update(['server_id_on_site' => $id]);
    }

    /**
     * Returns the ID of the server on the foreign site
     * @param Server $server
     * @return int
     */
    public function getServerId(Server $server): string {
        return $this->getServerMetaDBRow($server)
            ->select('server_id_on_site')
            ->get()->first()->server_id_on_site;
    }

    private function getServerMetaDBRow(Server $server): Builder{
        return Db::table('server_vote_site')
            ->where('server_id', $server->id)
            ->where('vote_site_id', $this->id);
    }

    public function getServerUrlOnSite(Server $server):string{
        $serverId = $this->getServerId($server);
        return Str::of($this->server_url)->replace($this->SERVER_ID_PLACEHOLDER,$serverId);
    }

}
