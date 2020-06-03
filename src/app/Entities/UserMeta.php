<?php

namespace VCComponent\Laravel\User\Entities;

use Illuminate\Database\Eloquent\Model;
use VCComponent\Laravel\User\Entities\User;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class UserMeta.
 *
 * @package namespace VCComponent\Laravel\User\Entities;
 */
class UserMeta extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'key',
        'value',
    ];

    protected $table = 'user_meta';

    public function user()
    {
        if (isset(config('auth.providers.users')['model'])) {
            return $this->belongsTo(config('auth.providers.users.model'));
        } else {
            return $this->belongsTo(User::class);
        }
    }
}
