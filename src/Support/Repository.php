<?php

namespace Larfree\Support;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
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
     * @param int|null   $perPage
     * @param array|null $wheres
     * @param array|null $withs
     * @param array|null $withsCount
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate(int $perPage = null, array $wheres = null, array $withs = null, array $withsCount = null)
    {
        return $this->model->newQuery()->select($this->columns)
            ->when(! empty($wheres), function (Builder $builder) use ($wheres)
                {
                    $this->where($builder, $wheres);
                }
            )
            ->when(! empty($withs), function (Builder $builder) use ($withs)
                {
                    $builder->with($withs);
                }
            )
            ->when(! empty($withsCount), function (Builder $builder) use ($withsCount)
                {
                    $builder->withCount($withsCount);
                }
            )
            ->paginate($perPage ?: $this->perPage);
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
     *
     * @return Model
     */
    public function find(int $id, array $wheres = null)
    {
        /**
         * @var Model $model
         */
        $model = $this->model->newQuery()->select($this->columns)
            ->when(! empty($wheres), function (Builder $builder) use ($wheres)
                {
                    $this->where($builder, $wheres);
                }
            )
            ->find($id);

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
        /**
         * @var Model $model
         */
        $model = $this->model->newQuery()->select($this->columns)
            ->when(! empty($wheres), function (Builder $builder) use ($wheres)
                {
                    $this->where($builder, $wheres);
                }
            )
            ->find($id);

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
        foreach ($wheres as $column => $where)
        {
            if (is_array($where))
            {
                $operator = Arr::get($where, 0);

                $value = Arr::get($where, 1);

                $boolean = Arr::get($where, 2, 'and');

                if(Arr::get($where, 3, false) === true)
                {
                    $builder->where(function (Builder $builder) use ($column, $operator, $value, $boolean)
                        {
                            $builder->where($column, $operator, $value, $boolean);
                        }
                    );
                }
                else
                {
                    $builder->where($column, $operator, $value, $boolean);
                }
            }
            else
            {
                $builder->where($column, $where);
            }
        }
    }
}