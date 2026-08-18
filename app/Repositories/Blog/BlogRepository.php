<?php

namespace App\Repositories\Blog;

use App\Models\Blog;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Str;

class BlogRepository extends BaseRepository
{
    public function __construct(Blog $model)
    {
        $this->model = $model;
    }

    public function getLatest($limit = 4, $offset = 0)
    {
        return $this->model
                    ->with('user')
                    ->offset($offset)
                    ->take($limit)
                    ->latest()
                    ->get();
    }

    public function getPaginate($show = 10)
    {
        return $this->model
                    ->with('user')
                    ->latest()
                    ->paginate($show);
    }

    public function getArtikel($show = 10)
    {
        return $this->model
                    ->where('blog_categories_id','1')
                    ->where('status', 'publish')
                    ->with('user','blog_categories')
                    ->latest()
                    ->paginate($show);
    }

    public function getUpdate($show = 10)
    {
        return $this->model
                    ->with('user','blog_categories')
                    ->where('blog_categories_id','2')
                    ->where('status', 'publish')
                    ->latest()
                    ->paginate($show);
    }

    public function create($data)
    {
        if(empty($data['slug'])){
            $data = array_merge($data, [
                'user_id' => auth()->user()->id,
                "slug"  => str::slug($data['title'] . "-" . time()),
            ]);
        }else{
            
            $data = array_merge($data, [
                'user_id' => auth()->user()->id,
                "slug"  => $data['slug']
            ]);
        }
        

        return parent::create($data);
    }

    public function findBySlug($slug)
    {
        return $this->model
                    ->with('user')
                    ->where('slug',$slug)
                    ->firstOrFail();
    }
}
