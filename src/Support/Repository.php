<?php

namespace Larfree\Support;

use Closure;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Builder;
use Larfree\Exceptions\ModelNotFoundException;
use Larfree\Exceptions\DatabaseSaveFailedException;

abstract class Repository
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * @var array
     */
    protected $columns = ['id'];

    /**
     * @var array
     */
    protected $appends = [];

    /**
     * @var Builder
     */
    protected $builder;

    public function setModel(Model $model)
    {
        $this->model = $model;

        return $this;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function setColumns(array $columns)
    {
        $this->columns = $columns;

        return $this;
    }

    public function addColumns(array $columns)
    {
        $this->columns = array_merge($this->columns, $columns);

        return $this;
    }

    public function setAppends(array $appends)
    {
        $this->appends = $appends;

        return $this;
    }

    public function query()
    {
        $this->builder = $this->model->setAppends($this->appends)::query()->select($this->columns);

        return $this;
    }

    /**
     * @author iwulai
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function get()
    {
        return $this->builder->get();
    }

    /**
     * @author iwulai
     *
     * @param array $ids
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findMany(array $ids)
    {
        return $this->builder->findMany($ids);
    }

    /**
     * @author iwulai
     *
     * @param int|null $perPage
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate(int $perPage = null)
    {
        return $this->builder->paginate($perPage);
    }

    /**
     * @author iwulai
     *
     * @param string      $column
     * @param string|null $key
     *
     * @return \Illuminate\Support\Collection
     */
    public function pluck(string $column, string $key = null)
    {
        return $this->builder->pluck($column, $key);
    }

    /**
     * @author iwulai
     *
     * @param array $attributes
     *
     * @return $this
     * @throws DatabaseSaveFailedException
     */
    public function create(array $attributes)
    {
        $this->saveAttributes($attributes);

        return $this;
    }

    /**
     * @author iwulai
     *
     * @param int $id
     *
     * @return Model|null
     */
    public function find(int $id)
    {
        $model = $this->builder->find($id);

        if ($model instanceof Model)
        {
            $this->model = $model;
        }

        return $model;
    }

    /**
     * @author iwulai
     *
     * @return Model|null
     */
    public function first()
    {
        $model = $this->builder->first();

        if ($model instanceof Model)
        {
            $this->model = $model;
        }

        return $model;
    }

    /**
     * @author iwulai
     *
     * @param array $attributes
     *
     * @return $this
     * @throws DatabaseSaveFailedException
     */
    public function save(array $attributes)
    {
        $this->saveAttributes($attributes);

        return $this;
    }

    /**
     * @author iwulai
     *
     * @param int $id
     *
     * @return Model
     * @throws \Exception
     */
    public function delete(int $id)
    {
        if ($model = $this->find($id))
        {
            if ($model->delete())
            {
                return $model;
            }

            throw new DatabaseSaveFailedException();
        }

        throw new ModelNotFoundException();
    }

    /**
     * @author iwulai
     *
     * @param array $wheres
     *
     * @return $this
     */
    public function wheres(array $wheres)
    {
        foreach ($wheres as $column => $where)
        {
            if (is_array($where))
            {
                if (($callback = Arr::get($where, 'callback')) && $callback instanceof Closure)
                {
                    $callback($this->builder);
                }
                else
                {
                    $operator = Arr::get($where, 'operator');

                    $value = Arr::get($where, 'value');

                    $boolean = Arr::get($where, 'boolean', 'and');

                    switch ($operator)
                    {
                        default :
                            $this->builder->where($column, $operator, $value, $boolean);
                        break;

                        case 'in' :
                            $this->builder->whereIn($column, $value, $boolean);
                        break;

                        case 'not in' :
                            $this->builder->whereNotIn($column, $value, $boolean);
                        break;
                    }
                }
            }
            else
            {
                $this->builder->where($column, $where);
            }
        }

        return $this;
    }

    /**
     * @author iwulai
     *
     * @param string     $column
     * @param int|string $left
     * @param int|string $right
     * @param string     $boolean
     * @param bool       $not
     *
     * @return $this
     */
    public function between($column, $left, $right, $boolean = 'and', $not = false)
    {
        $this->builder->whereBetween($column, [$left, $right], $boolean, $not);

        return $this;
    }

    /**
     * @author iwulai
     *
     * @param array $relations
     *
     * @return $this
     */
    public function with(array $relations)
    {
        $this->builder->with($relations);

        return $this;
    }

    /**
     * @author iwulai
     *
     * @param array $relations
     *
     * @return $this
     */
    public function withCount(array $relations)
    {
        $this->builder->withCount($relations);

        return $this;
    }

    /**
     * @author iwulai
     *
     * @param array $attributes
     *
     * @return $this
     * @throws DatabaseSaveFailedException
     */
    protected function saveAttributes(array $attributes)
    {
        $this->setAttributes($attributes);

        if (! $this->model->save())
        {
            throw new DatabaseSaveFailedException();
        }

        return $this;
    }

    /**
     * @author iwulai
     *
     * @param array $attributes
     *
     * @return $this
     */
    protected function setAttributes(array $attributes)
    {
        foreach ($attributes as $attribute => $value)
        {
            $this->model->setAttribute($attribute, $value);
        }

        return $this;
    }
}