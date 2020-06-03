<?php

namespace VCComponent\Laravel\User\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface UserRepository.
 *
 * @package namespace VCComponent\Laravel\User\Repositories;
 */
interface UserRepository extends RepositoryInterface
{
    public function getEntity();
    public function verifyEmail($user);
}
