<?php

namespace Larfree\Support;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Builder;
use Larfree\Exceptions\ModelNotFoundException;
use Larfree\Exceptions\DatabaseSaveFailedException;
use Larfree\Exceptions\PrimaryKeyNotFoundException;

abstract class Repository
{
    /**
     * @var Model
     */
    protected $model;

    protected $columns = ['id'];

    protected $perPage = 10;

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

    public function setPerPage(int $perPage)
    {
        $this->perPage = $perPage;

        return $this;
    }

    /**
     * @author iwulai
     *
     * @param array|null $wheres
     * @param array|null $withs
     * @param array|null $withsCount
     *
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function get(array $wheres = null, array $withs = null, array $withsCount = null)
    {
        $builder = $this->model->newQuery()->select($this->columns);

        $this->build($builder, $wheres, $withs, $withsCount);

        return $builder->get();
    }

    /**
     * @author iwulai
     *
     * @param int|null   $perPage
     * @param array|null $wheres
     * @param array|null $withs
     * @param array|null $withsCount
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate(int $perPage = null, array $wheres = null, array $withs = null, array $withsCount = null)
    {
        $builder = $this->model->newQuery()->select($this->columns);

        $this->build($builder, $wheres, $withs, $withsCount);

        return $builder->paginate($perPage ?: $this->perPage);
    }

    /**
     * @author iwulai
     *
     * @param string     $column
     * @param null       $key
     * @param array|null $wheres
     * @param array|null $withs
     * @param array|null $withsCount
     *
     * @return \Illuminate\Support\Collection
     */
    public function pluck(string $column, $key = null, array $wheres = null, array $withs = null, array $withsCount = null)
    {
        $builder = $this->model->newQuery()->select($this->columns);

        $this->build($builder, $wheres, $withs, $withsCount);

        return $builder->pluck($column, $key);
    }

    /**
     * @author iwulai
     *
     * @param array $attributes
     *
     * @return Model
     * @throws DatabaseSaveFailedException
     */
    public function create(array $attributes)
    {
        $this->saveAttributes($attributes);

        return $this->model;
    }

    /**
     * @author iwulai
     *
     * @param int        $id
     * @param array|null $wheres
     * @param array|null $append
     *
     * @return Model
     */
    public function find(int $id, array $wheres = null, array $appends = null)
    {
        $builder = $this->model->newQuery()->select($this->columns);

        if ($wheres)
        {
            $this->where($builder, $wheres);
        }
        /**
         * @var Model $model
         */
        $model = $builder->find($id);

        if ($appends)
        {
            $model->setAppends($appends);
        }

        return $model;
    }

    /**
     * @author iwulai
     *
     * @param array $attributes
     *
     * @return Model
     * @throws DatabaseSaveFailedException
     * @throws PrimaryKeyNotFoundException
     */
    public function save(array $attributes)
    {
        if (! $this->hasPrimaryKey())
        {
            throw new PrimaryKeyNotFoundException();
        }

        $this->saveAttributes($attributes);

        return $this->model;
    }

    /**
     * @author iwulai
     *
     * @param int        $id
     * @param array|null $wheres
     *
     * @return Model
     * @throws DatabaseSaveFailedException
     * @throws ModelNotFoundException
     * @throws \Exception
     */
    public function delete(int $id, array $wheres = null)
    {
        $builder = $this->model->newQuery()->select($this->columns);

        if ($wheres)
        {
            $this->where($builder, $wheres);
        }
        /**
         * @var Model $model
         */
        $model = $builder->find($id);

        if (is_null($model))
        {
            throw new ModelNotFoundException();
        }

        if (! $model->delete())
        {
            throw new DatabaseSaveFailedException();
        }

        return $model;
    }

    /**
     * @author iwulai
     *
     * @return bool
     */
    protected function hasPrimaryKey()
    {
        return !! $this->model->getAttributeValue('id');
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

    protected function where(Builder $builder, array $wheres)
    {
        $this->beforeWhere($builder);

        $builder->where(function (Builder $builder) use ($wheres)
            {
                foreach ($wheres as $column => $where)
                {
                    if (is_array($where))
                    {
                        $operator = Arr::get($where, 0);

                        $value = Arr::get($where, 1);

                        $boolean = Arr::get($where, 2, 'and');

                        $builder->where($column, $operator, $value, $boolean);
                    }
                    else
                    {
                        $builder->where($column, $where);
                    }
                }

                $this->keepWhere($builder);
            }
        );

        $this->afterWhere($builder);
    }

    protected function build(Builder $builder, array $wheres = null, array $withs = null, array $withsCount = null)
    {
        if ($wheres)
        {
            $this->where($builder, $wheres);
        }

        if ($withs)
        {
            $builder->with($withs);
        }

        if ($withsCount)
        {
            $builder->with($withsCount);
        }

        return $this;
    }

    protected function beforeWhere(Builder $builder)
    {
        return $this;
    }

    protected function keepWhere(Builder $builder)
    {
        return $this;
    }

    protected function afterWhere(Builder $builder)
    {
        return $this;
    }
}