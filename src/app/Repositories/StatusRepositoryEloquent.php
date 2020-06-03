<?php

namespace VCComponent\Laravel\User\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use VCComponent\Laravel\User\Repositories\StatusRepository;
use VCComponent\Laravel\User\Entities\Status;
use VCComponent\Laravel\User\Validators\StatusValidator;

/**
 * Class StatusRepositoryEloquent.
 *
 * @package namespace VCComponent\Laravel\User\Repositories;
 */
class StatusRepositoryEloquent extends BaseRepository implements StatusRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Status::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
