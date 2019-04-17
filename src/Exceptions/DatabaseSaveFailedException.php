<?php

namespace Larfree\Exceptions;

class DatabaseSaveFailedException extends ApiException
{
    protected $message = '服务异常！数据保存失败，请重试。';
}