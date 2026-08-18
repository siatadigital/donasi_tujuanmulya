<?php

namespace App\Repositories\Update;

use App\Models\Update;
use App\Repositories\Base\BaseRepository;

class UpdateRepository extends BaseRepository
{
    public function __construct(Update $update)
    {
        $this->model = $update;
    }

    public function findById($id){
    	return $this->model
    					->with('project')
    					// ->whereRaw('projects.project_id', 'updates.project_id')
    					->where('id', $id)
    					->firstOrFail();;
    }

    public function createUpdate($data){
    	$model = $this->model;
    	$this->_save($model, $data);
    	return $model;
    }

    public function editUpdate($model, $data){
    	$this->_save($model, $data);
    	return $model;
    }

    private function _save($model, $data){
        $model->fill($data);
        $model->save();
        return $model;
    }
}
