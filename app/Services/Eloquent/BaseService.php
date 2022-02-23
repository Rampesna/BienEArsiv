<?php

namespace App\Services\Eloquent;

use Illuminate\Database\Eloquent\Model;

class BaseService
{
    private $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function getAll()
    {
        return $this->model->all();
    }

    public function getById($id)
    {
        return $this->model->find($id) ?? 404;
    }
}
