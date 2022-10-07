<?php

namespace App;

use DateTime;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Auth\Authenticatable as Authenticable;
use Watson\Validating\ValidatingTrait;
use xPaw\MinecraftPing;

/**
 * @property User ownedBy
 * @property string name
 * @property string ip
 * @property string description
 * @property int port
 * @property Image banner
 * @property Image logo
 * @property integer playerCount
 * @property Version version
 * @property int maxPlayers
 * @property bool online
 * @property Collection types
 * @property string website_url
 */
class Server extends Model implements AuthenticatableContract
{
    use HasApiTokens, Authenticable, ValidatingTrait;
    public static $current = null;
    protected $fillable = [
        'name', 'ip', 'description', 'port',
        'playerCount','maxPlayers', 'online',
        'website_url','version_id',
    ];

    protected $casts = [
        'votifier_settings_id' => 'integer',
    ];

    protected $rules = [
        'name' => ['required','unique:servers,name'],
        'ip'=> 'required',
        'description' => 'required',
        'port'=> 'nullable|integer|min:0|max:65535',
        'website_url' => 'nullable|url',
        'version_id' => 'required|exists:versions,id',
    ];
    protected $throwValidationExceptions = true;

    protected $attributes = [
        'port' => 25565,
        'votifier_settings_id' => 0
    ];

    public function voteSites(){
        return $this->belongsToMany(VoteSite::class);
    }

    public function ownedBy(){
        return $this->belongsTo(User::class);
    }

    public function banner(){
        return $this->belongsTo(Image::class);
    }

    public function setPortAttribute($value){
        $this->attributes['port'] = empty($value)?25565:$value;
    }

    public function setBannerAttribute(Image $banner){
        if ($this->banner != null){
            $this->banner->delete();
        }
        $this->banner()->associate($banner);
    }

    public function logo(){
        return $this->belongsTo(Image::class);
    }

    public function setLogoAttribute(Image $logo){
        if ($this->logo != null){
            $this->logo->delete();
        }
        $this->logo()->associate($logo);
    }

    public function isOwnedBy(User $authentifiedEntity){
        return $this->ownedBy->id === $authentifiedEntity->id;
    }

    public function version(){
        return $this->belongsTo(Version::class);
    }

    public function types(){
        return $this->belongsToMany(Type::class);
    }

    public function isVotifierEnabled(){
        return $this->getAttribute('votifier_settings_id') !== 0;
    }

    public function votifierSettings(){
        return $this->belongsTo(VotifierSettings::class)->withDefault([
            'ip' => $this->ip,
            'token' => ''
        ]);
    }

    public function updateIfNeeded(){
        if ($this->pingCacheExpired()){
            $this->updateServer();
        }
    }

    private function pingCacheExpired(){
        return $this->updated_at->add(new \DateInterval('PT10M')) < new Datetime();
    }

    private function updateServer(){
        try {
            $server = new MinecraftPing($this->ip, $this->port);
            $data = $server->query();
            $this->maxPlayers = $data['players']['max'];
            $this->playerCount = $data['players']['online'];
            $this->online = true;
        } catch (\Exception $e){
            $this->playerCount = 0;
            $this->online = false;
        }
        $this->update();
    }

    private function cachedPrefix():string{
        return "server".$this->id.".";
    }

    public static function getByNameOrFail($name): Server{
        return Server::where('name',$name)->firstOrFail();
    }

    public static function current():Server{
        return self::$current;
    }

    public static function setCurrent(Server $server){
        self::$current = $server;
    }
}
