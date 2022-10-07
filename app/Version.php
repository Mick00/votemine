<?php

namespace App;

use Carbon\Traits\Date;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Version
 * @package App
 * @property int $id
 * @property string $number
 * @property Date $release_date
 */
class Version extends Model
{
    protected $fillable = ['number', 'release_date'];

    protected $dates = ['release_date'];

    protected static function booted(){
        static::addGlobalScope('OrderByRelease', function (Builder $builder) {
            $builder->orderByDesc('release_date');
        });
    }

    public static function toSelectOptions(){
        $versions = Version::all();
        return $versions->map(function (Version $v){
            return [
                'value'=> $v->id,
                'label'=>$v->number,
            ];
        })->toArray();
    }
}
