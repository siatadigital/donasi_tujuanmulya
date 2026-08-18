<?php

namespace App\Repositories\Base;

use App\Models\Option;

class OptionData
{
    protected $data = [];

    public function __construct(Option $option)
    {
        $this->model = $option;
    }

    public function get($key, $default = null)
    {
        if (!$this->data) {
            $this->data = $this->getAutoloadData();
        }

        if (!empty($this->data[$key])) {
            return $this->data[$key];
        }

        return $default;
    }

    public function set($key, $value)
    {
        // save the change to the db
        $this->model->updateOrCreate(compact('key'), compact('value'));

        // if we've loaded the data, persist this change
        if ($this->data) {
            $this->data[$key] = $value;
        }
    }

    /**
     * Get all option data
     *
     * @return Collection
     */
    public function getAll()
    {
    	if (!$this->data) {
            return $this->getAutoloadData();
        }
        return $this->data;
    }

    private function getAutoloadData()
    {
        $result = [];
        $data = $this->model->get()->toArray();
        foreach ($data as $key => $value) {
            $result[$value['key']] = $value['value'];
        }
        return $result;
    }
}
