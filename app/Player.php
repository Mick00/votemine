<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Watson\Validating\ValidatingTrait;

class Player extends Model
{
    use ValidatingTrait;

    protected $fillable = [
        'name', 'uuid','ipv4','ipv6'
    ];

    protected $rules = [
        'name' => 'required|string|alpha_dash|min:3|max:16'
    ];
    protected $throwValidationExceptions = true;
    protected $hidden = [
        'id', 'created_at', 'updated_at', 'uuid'
    ];

    protected $casts = [
        'name' => 'string'
    ];
}
