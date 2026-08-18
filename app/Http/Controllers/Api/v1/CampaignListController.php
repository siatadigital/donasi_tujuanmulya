<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Project;

class CampaignListController extends Controller
{
    public function list()
    {
        $projects = Project::query()
            ->latest('updated_at')
            //->where('id', '>', request('starting_id', 0))
            ->take(request('take', 100))
            ->get();

        return response()->json($projects);
    }

    public function latestUpdate()
    {
        $projects = Project::query()
            ->latest('updated_at')
            ->take(100)
            ->get();

        return response()->json($projects);
    }

    public function category()
    {
        return response()->json(Category::get());
    }
}
