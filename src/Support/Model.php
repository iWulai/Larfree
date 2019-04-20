<?php

namespace Larfree\Support;

use Illuminate\Database\Eloquent\Model as BaseModel;

abstract class Model extends BaseModel
{
    public function newInstance($attributes = [], $exists = false)
    {
        $model = parent::newInstance($attributes, $exists);

        $model->appends = $this->getArrayableAppends();

        return $model;
    }
}