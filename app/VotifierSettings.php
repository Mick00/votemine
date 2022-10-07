<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Imaginarycode\Votifier2\Vote;
use Watson\Validating\ValidatingTrait;

/**
 * Class VotifierSettings
 * @package App
 * @property int $id
 * @property int port
 * @property string token
 */
class VotifierSettings extends Model
{
    use ValidatingTrait;
    protected $fillable = [
        'ip', 'port', 'token'
    ];

    protected $attributes = [
        'port'=>8192
    ];

    protected $rules = [
        'port'=> 'nullable|integer|min:0|max:65535',
        'ip'=> 'required',
        'token' =>'required'
    ];

    protected $throwValidationExceptions = true;
}
