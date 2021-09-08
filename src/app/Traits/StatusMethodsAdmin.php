<?php

namespace VCComponent\Laravel\User\Traits;

use Illuminate\Http\Request;
use VCComponent\Laravel\User\Entities\Status;
use VCComponent\Laravel\User\Transformers\StatusTransformer;

trait StatusMethodsAdmin
{

    public function index(Request $request)
    {
        $query = new Status;

        $query = $this->applySearchFromRequest($query, ['name'], $request);
        $query = $this->applyConstraintsFromRequest($query, $request);
        $query = $this->applyOrderByFromRequest($query, $request);

        $per_page = $request->get('per_page') ? (int) $request->get('per_page') : 15;
        $statuses = $query->paginate($per_page);

        if ($request->has('includes')) {
            $transformer = new StatusTransformer(explode(',', $request->get('includes')));
        } else {
            $transformer = new StatusTransformer();
        }

        return $this->response->paginator($statuses, $transformer);
    }

    public function list(Request $request)
    {
        $query = new Status;

        $query = $this->applySearchFromRequest($query, ['name'], $request);
        $query = $this->applyConstraintsFromRequest($query, $request);
        $query = $this->applyOrderByFromRequest($query, $request);

        $statuses = $query->get();

        if ($request->has('includes')) {
            $transformer = new StatusTransformer(explode(',', $request->get('includes')));
        } else {
            $transformer = new StatusTransformer();
        }

        return $this->response->collection($statuses, $transformer);
    }

    public function show(Request $request, $id)
    {
        $status = $this->repository->find($id);

        if ($request->has('includes')) {
            $transformer = new StatusTransformer(explode(',', $request->get('includes')));
        } else {
            $transformer = new StatusTransformer();
        }

        return $this->response->item($status, $transformer);
    }

    public function store(Request $request)
    {
        $this->validator->isValid($request, 'RULE_CREATE');

        $data   = $request->all();
        $status = $this->repository->create($data);

        return $this->response->item($status, new StatusTransformer());
    }

    public function update(Request $request, $id)
    {
        $this->validator->isValid($request, 'RULE_UPDATE');

        $data   = $request->all();
        $status = $this->repository->update($data, $id);

        return $this->response->item($status, new StatusTransformer());
    }

    public function destroy($id)
    {
        $this->repository->delete($id);
        return $this->success();
    }
}
