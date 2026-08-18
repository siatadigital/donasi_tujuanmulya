<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\Base\BaseRepository;

class UserRepository extends BaseRepository
{
    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function getAll($limit = 10,$offset = 0)
    {
        return $this->model->offset($offset)
                    ->orderBy('created_at','desc')
                    ->paginate($limit);
    }

    public function findByUsername($username)
    {
        // Username should not include space
        // but it was included and encoded
        // so it needs to decoded (%20 to real space)
        $decodedUsername = urldecode($username);

        return $this->model->where('username', $decodedUsername)->firstOrFail();
    }

    public function getArtist($limit = 6)
    {
        return $this->model
                    ->where('is_artist',1)
                    ->orderBy('created_at','desc')
                    ->paginate($limit);
    }

    public function searchArtist($keyword, $limit = 12)
    {
        return $this->model
                    ->where('is_artist', 1)
                    ->where('name', 'LIKE', "%$keyword%")
                    // ->orWhere('username', 'LIKE', "%$keyword%")
                    ->paginate($limit);
    }

    public function countSupportingProject(User $user)
    {
        $user_project_id = $user->supporting()->lists('project_id')->toArray();
        return app('App\Models\Project')->whereIn('id', $user_project_id)->count();
    }

    public function saveFotoKtp($username, $data, $verify_status)
    {
        $user = $this->model->where('username', $username)->firstOrFail();        
        $user->fotoktp = $data['fotoktp'];               
        $user->foto_with_ktp = $data['foto_with_ktp'];    
        $user->name = $data['name'];        
        $user->birth_date = $data['birth_date'];        
        $user->gender = $data['gender'];        
        $user->province = $data['province'];        
        $user->city = $data['city'];      
        $user->type_akun = $data['type_akun'];
        $user->is_verified = $verify_status;
        $user->save();
        
        return $user;
    }

    
    /**
     * Count Project
     *
     * @param  User   $user
     * @param  string(active,pending,all) $type
     * @return integer
     */
    public function countProject(User $user, $type = 'active')
    {
        $query = app('App\Models\Project')->where('user_id', $user->id);

        if ($type == 'active') {
            $query = $query->active();
        } elseif ($type == 'pending') {
            $query = $query->pending();
        }

        return $query->count();
    }

    public function countMedia(User $user)
    {
        return app('App\Models\Media')->where('user_id', $user->id)->count();
    }

    public function getSupportingProject(User $user, $limit = 10)
    {
        $user_project_id = $user->supporting()->lists('project_id')->toArray();
        return app('App\Models\Project')
            ->whereIn('id', $user_project_id)
            ->with('user')
            ->latest()
            ->paginate($limit);
    }

    public function getPhotos($user_id, $limit = 10)
    {
        return app('App\Models\Media')->where('user_id', $user_id)->latest()->paginate($limit);
    }

    public function update($identifier, $input)
    {
        if ($identifier instanceof User) {
            $model = $identifier;
        } else {
            $model = $this->find($identifier);
        }

        $model->fill($input);
        if (str_contains(@$input['phone'], '+62')) {
            $model->phone = str_replace('+62', '', @$input['phone']);
        } elseif (substr(@$input['phone'], 0, 1) == 0) {
            $model->phone = str_replace(0, '', @$input['phone']);
        } else {
            $model->phone = @$input['phone'];
        }

        $model->save();
        return $model;
    }
}
