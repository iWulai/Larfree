<?php

namespace Larfree;

use ArrayAccess;
use Illuminate\Support\Arr;

class Request implements ArrayAccess
{
    protected $attributes;

    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    public static function make(array $attributes = [])
    {
        return new static($attributes);
    }

    public function get($name, $default = null)
    {
        return Arr::get($this->attributes, $name, $default);
    }

    public function __get($name)
    {
        return $this->get($name);
    }

    public function all()
    {
        return $this->attributes;
    }

    public function pull(string $key, $default = null)
    {
        return Arr::pull($this->attributes, $key, $default);
    }

    public function only(array $keys)
    {
        return Arr::only($this->attributes, $keys);
    }

    public function except(array $keys)
    {
        return Arr::except($this->attributes, $keys);
    }

    public function offsetExists($offset)
    {
        return Arr::has($this->attributes, $offset);
    }

    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    public function offsetSet($offset, $value)
    {
        Arr::set($this->attributes, $offset, $value);

        return $this;
    }

    public function offsetUnset($offset)
    {
        unset($this->attributes[$offset]);

        return $this;
    }
}