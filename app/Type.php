<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Class Type
 * @package App
 * @property integer $id
 * @property string slug
 * @property string name
 */
class Type extends Model
{
    const CHECKBOX_PREFIX = "type_";
    protected $fillable = [ 'slug', 'name' ];
    public function servers(){
        return $this->belongsToMany(Server::class);
    }

    public function getCheckboxName(){
        return Type::CHECKBOX_PREFIX.$this->slug;
    }

    public static function toCheckboxes(Server $server = null){
        $versions = Type::all();
        return $versions->map(function (Type $v) use ($server){
            return [
                'name'=> $v->getCheckboxName(),
                'label'=> __($v->name),
                'value'=> $v->id,
                'checked' => $server!=null?$server->types->contains($v):false,
            ];
        })->toArray();
    }


    public static function getCheckedTypesFromInput($inputs){
        $types = array_filter($inputs,function ($value, $key){
            return Str::startsWith($key, Type::CHECKBOX_PREFIX);
        },ARRAY_FILTER_USE_BOTH);
        return Type::find(array_values($types));
    }
}
