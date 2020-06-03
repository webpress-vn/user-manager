<?php

namespace VCComponent\Laravel\User\Entities;

use Illuminate\Database\Eloquent\Model;
use VCComponent\Laravel\User\Entities\User;

class Gender extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'value',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
