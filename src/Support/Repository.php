<?php

namespace Larfree\Support;

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

    public function setColumns(array $columns)
    {
        $this->columns = $columns;

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
     * @param array|null $where
     * @param array|null $with
     * @param array|null $withCount
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate(int $perPage = null, array $where = null, array $with = null, array $withCount = null)
    {
        return $this->model->newQuery()->select($this->columns)
            ->when(! empty($where), function (Builder $builder) use ($where)
                {
                    $builder->where($where);
                }
            )
            ->when(! empty($with), function (Builder $builder) use ($with)
                {
                    $builder->with($with);
                }
            )
            ->when(! empty($withCount), function (Builder $builder) use ($withCount)
                {
                    $builder->withCount($withCount);
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
     * @param array|null $where
     *
     * @return Model
     */
    public function find(int $id, array $where = null)
    {
        /**
         * @var Model $model
         */
        $model = $this->model->newQuery()->select($this->columns)
            ->when(! empty($where), function (Builder $builder) use ($where)
                {
                    return $builder->where($where);
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
     * @param array|null $where
     *
     * @return Model
     * @throws DatabaseSaveFailedException
     * @throws ModelNotFoundException
     * @throws \Exception
     */
    public function delete(int $id, array $where = null)
    {
        /**
         * @var Model $model
         */
        $model = $this->model->newQuery()->select($this->columns)
            ->when(! empty($where), function (Builder $builder) use ($where)
                {
                    return $builder->where($where);
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
}