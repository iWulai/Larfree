<?php

namespace Larfree;

use Exception;
use Illuminate\Support\Arr;
use Larfree\Exceptions\{ModelNotFoundException, DatabaseSaveFailedException};

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
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected $query;

    public function __construct(Model $model)
    {
        $this->model = $model;

        $this->query();
    }

    public function getModelTable()
    {
        return $this->model->getTable();
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

    public function newQuery()
    {
        return $this->query();
    }

    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @author iwulai
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function get()
    {
        return $this->query->get();
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
        return $this->query->findMany($ids);
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
        return $this->query->paginate($perPage);
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
        return $this->query->pluck($column, $key);
    }

    /**
     * @author iwulai
     *
     * @param array $attributes
     *
     * @return Model
     *
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
     * @param int  $id
     * @param bool $return
     *
     * @return $this|Model|null
     *
     * @throws ModelNotFoundException
     */
    public function find(int $id, bool $return = true)
    {
        /**
         * @var Model $model
         */
        $model = $this->query->find($id);

        $instanceof = $model instanceof Model;

        if ($instanceof) $this->model = $model;

        if ($return) return $model;

        if (! $instanceof) throw new ModelNotFoundException();

        return $this;
    }

    /**
     * @author iwulai
     *
     * @return Model
     */
    public function first()
    {
        $model = $this->query->first();

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
     * @return Model
     *
     * @throws DatabaseSaveFailedException
     */
    public function save(array $attributes)
    {
        $this->saveAttributes($attributes);

        return $this->model;
    }

    /**
     * @author iwulai
     *
     * @param int $id
     *
     * @return Model|Repository|null
     *
     * @throws DatabaseSaveFailedException
     * @throws ModelNotFoundException
     */
    public function delete(int $id)
    {
        if ($model = $this->find($id))
        {
            try
            {
                if ($deleted = $model->delete())
                {
                    return $model;
                }
            }
            catch (Exception $exception)
            {
                throw new ModelNotFoundException();
            }

            if (! $deleted) throw new DatabaseSaveFailedException();
        }

        throw new ModelNotFoundException();
    }

    /**
     * @author iwulai
     *
     * @param array $values
     *
     * @return int
     */
    public function update(array $values)
    {
        return $this->query->update($values);
    }

    /**
     * @author iwulai
     *
     * @param array $wheres
     * @param bool  $and
     *
     * @return $this
     */
    public function wheres(array $wheres, bool $and = true)
    {
        $query = $this->model->newModelQuery()->getQuery();

        foreach ($wheres as $column => $where)
        {
            if (is_array($where))
            {
                $operator = Arr::get($where, 'operator');

                $value = Arr::get($where, 'value');

                $boolean = Arr::get($where, 'boolean', 'and');

                $query->where($column, $operator, $value, $boolean);
            }
            else
            {
                $query->where($column, $where);
            }
        }

        $this->query->addNestedWhereQuery($query, $and ? 'and' : 'or');

        return $this;
    }

    /**
     * @author iwulai
     *
     * @param string|array|\Closure $column
     * @param int|string|null       $operator
     * @param int|string|array|null $value
     * @param string                $boolean
     *
     * @return $this
     */
    public function where($column, $operator = null, $value = null, string $boolean = 'and')
    {
        $this->query->where($column, $operator, $value, $boolean);

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
    public function whereBetween(string $column, $left, $right, string $boolean = 'and', bool $not = false)
    {
        $this->query->whereBetween($column, [$left, $right], $boolean, $not);

        return $this;
    }

    /**
     * @author iwulai
     *
     * @param string $column
     * @param array  $values
     * @param string $boolean
     * @param bool   $not
     *
     * @return $this
     */
    public function whereIn(string $column, array $values, string $boolean = 'and', bool $not = false)
    {
        $this->query->whereIn($column, $values, $boolean, $not);

        return $this;
    }

    /**
     * @author iwulai
     *
     * @param string $keyword
     * @param array  $columns
     * @param bool   $leftLike
     *
     * @return $this
     */
    public function search(string $keyword, array $columns, bool $leftLike = false)
    {
        $query = $this->model->newModelQuery()->getQuery();

        $keyword = $keyword . '%';

        if ($leftLike)
        {
            $keyword = '%' . $keyword;
        }

        $boolean = 'and';

        foreach ($columns as $column)
        {
            $query->where($column, 'like', $keyword, $boolean);

            $boolean = 'or';
        }

        $this->query->addNestedWhereQuery($query);

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
        $this->query->with($relations);

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
        $this->query->withCount($relations);

        return $this;
    }

    /**
     * @author iwulai
     *
     * @return $this
     */
    protected function query()
    {
        $this->query = $this->model->setAppends($this->appends)->newQuery()->select($this->columns);

        return $this;
    }

    /**
     * @author iwulai
     *
     * @param array $attributes
     *
     * @return $this
     *
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