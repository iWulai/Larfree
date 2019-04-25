<?php

namespace Larfree;

use Illuminate\Database\Eloquent\Model as BaseModel;

abstract class Model extends BaseModel
{
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public function newInstance($attributes = [], $exists = false)
    {
        return parent::newInstance($attributes, $exists)->setAppends($this->appends);
    }
}