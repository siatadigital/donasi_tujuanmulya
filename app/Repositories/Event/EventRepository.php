<?php

namespace App\Repositories\Event;

use App\Models\Event;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Str;

class EventRepository extends BaseRepository
{
    public function __construct(Event $model)
    {
        $this->model = $model;
    }

    public function getLatest($limit = 4, $offset = 0)
    {
        return $this->model
                    ->with('user', 'attendances')
                    ->offset($offset)
                    ->take($limit)
                    ->latest()
                    ->get();
    }

    public function getPaginate($show = 10)
    {
        return $this->model
                    ->with('user', 'attendances')
                    ->latest()
                    ->paginate($show);
    }

    public function create($data)
    {
        $data = array_merge($data, [
            'user_id' => auth()->user()->id,
            "slug"  => str::slug($data['title'] . "-" . time()),
            "schedule" => date("Y-m-d H:i:s",strtotime($data['schedule'])),
        ]);
        return parent::create($data);
    }

    public function findBySlug($slug)
    {
        return $this->model
                    ->with('user', 'attendances')
                    ->where('slug',$slug)
                    ->firstOrFail();
    }

    public function related($slug, $limit = 4, $offset = 0)
    {
        return $this->model
                    ->with('user', 'attendances')
                    ->where('slug', '<>', $slug)
                    ->offset($offset)
                    ->take($limit)
                    ->orderByRaw("RAND()")
                    ->get();
    }
}
