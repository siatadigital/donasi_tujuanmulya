<?php

namespace App\Repositories\Base;

use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
{

    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function setModel($model)
    {
        $this->model = $model;
        return $this;
    }

    /**
     * Get all data
     *
     * @param integer limit
     * @return array
     */
    public function getAll($limit = null, $offset = 0)
    {
        return $this->model->offset($offset)
                    ->take($limit)
                    ->orderBy('id', 'desc')
                    ->get();
    }

    /**
     * Get by where
     *
     * @param  array  $where
     * @return
     */
    public function getWhere(array $where)
    {
        $query = $this->model;
        foreach ($where as $field => $value) {
            if (is_array($value)) {
                list($field, $condition, $val) = $value;
                $query = $query->where($field, $condition, $val);
            } else {
                $query = $query->where($field, '=', $value);
            }
        }
        return $query->get();
    }

    /**
     * Get data by key in multiple value
     *
     * @param mix $key
     * @param array $value
     * @return array
     */
    public function getWhereIn($key, array $value = [])
    {
        return $this->model->whereIn($key, $value)->latest()->get();
    }

    /**
     * Get total from currently query
     *
     * @return integer
     */
    public function getCount()
    {
        return $this->model->count();
    }

    /**
     * Get data as paginate
     *
     * @param integer $limit
     * @param integer $offset
     * @return array
     */
    public function getPaginate($limit = 10)
    {
        return $this->model->orderBy('id', 'desc')->paginate($limit);
    }

    /**
     * Get single data by id
     *
     * @param  integer|string $id
     * @return array
     */
    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Get single data by id or other
     *
     * @param  mix(integer|string) $identifier
     * @return
     */
    public function findByIdentifier($identifier, $field_identifier = 'slug')
    {
        return $this->model
                    ->where('id', $identifier)
                    ->orWhere($field_identifier, $identifier)
                    ->firstOrFail();
    }

    /**
     * Insert data on database
     *
     * @param mix $data
     * @return instance
     */
    public function create($data)
    {
        return $this->model->create($data);
    }

    /**
     * Update data
     *
     * @param mix(int|Model) $identifier
     * @param array $data
     *
     * @return instance
     */
    public function update($identifier, $data)
    {
        if ($identifier instanceof Model) {
            $model = $identifier;
        } else {
            $model = $this->find($identifier);
        }

        $model->update($data);

        return $model;
    }

    /**
     * Restore deleted data. only work for softDeletes model
     *
     * @param  integer $id
     * @return Model
     */
    public function restore($id)
    {
        $model = $this->model->findOrFail($id);
        return $model->restore();
    }

    /**
     * Soft/Hard Delete data
     *
     * @param  integer $id
     * @param  boolean $forceDelete
     * @return boolean
     */
    public function delete($id, $forceDelete = false)
    {
        $model = $this->model->findOrFail($id);

        if ($forceDelete) {
            if ($model->forceDelete()) {
                return $model;
            }
        } else {
            if ($model->delete()) {
                return $model;
            }
        }
        return false;
    }
}
