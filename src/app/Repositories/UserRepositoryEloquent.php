<?php

namespace VCComponent\Laravel\User\Repositories;

use Carbon\Carbon;
use Prettus\Repository\Contracts\CacheableInterface;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Traits\CacheableRepository;
use VCComponent\Laravel\User\Entities\User;
use VCComponent\Laravel\User\Repositories\CanFlushCache;
use VCComponent\Laravel\User\Repositories\UserRepository;

/**
 * Class UserRepositoryEloquent.
 *
 * @package namespace VCComponent\Laravel\User\Repositories;
 */
class UserRepositoryEloquent extends BaseRepository implements UserRepository, CacheableInterface
{
    use CacheableRepository, CanFlushCache;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        if (isset(config('auth.providers.users')['model'])) {
            return config('auth.providers.users.model');
        } else {
            return User::class;
        }
    }

    public function getEntity()
    {
        return $this->model;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    // public function existsCredential($id, $value)
    // {
    //     $credential = VCCAuth::getCredentialField();

    //     $user = $this->findWhere([
    //         $credential => $value,
    //         ['id', '!=', $id],
    //     ])->first();
    //     if (!$user) {
    //         return false;
    //     } else {
    //         return $user;
    //     }
    // }

    public function verifyEmail($user)
    {
        $user->email_verified    = 1;
        $user->status            = 1;
        $user->email_verified_at = Carbon::now();
        $user->save();
        $this->flushCache();
        return $user;
    }


    // public function attachRole($role, $id)
    // {
    //     $user = $this->find($id);
    //     if ($user->roles->isEmpty()) {
    //         $role = Role::where('slug', $role)->first();
    //         $user->attachRole($role);
    //     }
    //     return $user;
    // }
}
