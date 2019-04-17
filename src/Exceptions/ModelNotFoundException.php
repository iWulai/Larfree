<?php

namespace Larfree\Exceptions;

class ModelNotFoundException extends ApiException
{
    protected $message = '数据异常！该数据不存在或已删除。';
}