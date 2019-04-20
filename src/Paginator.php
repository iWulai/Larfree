<?php

namespace Larfree;

class Paginator
{
    public $perPage;

    public $page;

    public function __construct(int $perPage = null, int $page = null)
    {
        $this->perPage = $perPage;

        $this->page = $page;
    }

    public static function make(int $perPage = null, int $page = null)
    {
        return new static($perPage, $page);
    }
}