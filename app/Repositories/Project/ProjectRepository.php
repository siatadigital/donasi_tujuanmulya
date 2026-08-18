<?php

namespace App\Repositories\Project;

use App\Models\Project;
use App\Models\ProjectWithdraw;
use App\Models\Supporter;
use App\Repositories\Base\BaseRepository;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Str;

class ProjectRepository extends BaseRepository
{
    public function __construct(Project $model)
    {
        $this->model = $model;
    }

    public function getPopular($limit = 3)
    {
        return $this->model
                    ->with('user')
                    ->where('projects.status', 'active')
                    ->where('is_fundraiser', 0)
                    ->leftJoin('supporters', 'supporters.project_id', '=', 'projects.id')
                    ->groupBy('projects.id')
                    ->orderBy('nsupporters', 'desc')
                    ->orderByRaw("abs(TIMEDIFF( NOW(), str_to_date(projects.time_end, '%Y-%m-%d %H:%i:%s'))) asc")
                    ->limit($limit)
                    ->get(['projects.*', DB::raw('count(supporters.id) as nsupporters')]);
    }

    /**
     * project terdanai
     * return data where project was success raise the target or the date expired
     *
     * @param  limit
     * @return Model
     */
    public function getLatest($limit = 4, $offset = 0)
    {
        return $this->model
                    ->with('user')
                    ->where('status', 'active')
                    ->whereRaw('money_progress >= money_target')
                    ->orWhereRaw("time_end < '" . date('Y-m-d G:i:s') . "'" )
                    ->offset($offset)
                    ->take($limit)
                    ->orderBy('money_progress', 'desc')
                    ->latest()
                    ->active()
                    ->limit($limit)
                    ->get();
    }

    /**
     * @param limit
     * get project yang sedang berjalan
     * where
     */
    public function getActive($limit = 4, $offset = 0)
    {
        return $this->model
                    ->with('user')
                    ->where('status', 'active')
                    ->whereRaw('money_progress < money_target')
                    ->where( 'time_end', '>=' , date('Y-m-d G:i:s') )
                    ->where('is_fundraiser', 0)
                    ->offset($offset)
                    ->take($limit)
                    // ->orderBy('money_progress', 'desc')
                    ->orderBy('created_at', 'desc')
                    ->latest()
                    ->active()
                    ->get();
    }

    /**
     * @param limit
     * get project yang sedang berjalan
     * where
     */
    public function getCountActive()
    {
        return $this->model
                    ->with('user')
                    ->where('status', 'active')
                    ->whereRaw('money_progress < money_target')
                    // ->where( 'time_end', '>=' , date('Y-m-d G:i:s') )
                    ->orderBy('created_at', 'desc')
                    ->latest()
                    ->active()
                    ->count();
    }

    /**
     * @param limit
     * get project yang aktif dan featured
     * where
     */
    public function getFeatured($limit = 4, $offset = 0)
    {
        return $this->model
                    ->with('user')
                    ->where('status', 'active')
                    ->whereRaw('money_progress < money_target')
                    ->where( 'time_end', '>=' , date('Y-m-d G:i:s') )
                    ->where('is_featured', 1)
                    ->where('is_fundraiser', 0)
                    ->offset($offset)
                    ->take($limit)
                    // ->orderBy('money_progress', 'desc')
                    ->orderBy('created_at', 'desc')
                    ->latest()
                    ->active()
                    ->get();
    }

    /**
     * @param limit
     * get project yang terdanai yang selesai/berakhir
     * where
     */
    public function getFinish($limit = 4, $offset = 0)
    {
        return $this->model
                    ->with('user')
                    ->where('status', 'active')
                    ->where( 'time_end', '<' , date('Y-m-d G:i:s') )
                    ->offset($offset)
                    ->take($limit)
                    ->orderBy('created_at', 'desc')
                    ->latest()
                    ->active()
                    ->get();
    }

    public function getActivePaginate($limit = 10)
    {
        return $this->model->with('user')->latest()->active()->paginate($limit);
    }

    public function getActivePaginateByUser($user_id, $limit = 10)
    {
        return $this->model->with('user')->where('user_id', $user_id)->latest()->active()->paginate($limit);
    }

    public function getAllPaginateByUser($user_id, $limit = 10)
    {
        return $this->model->with('user')->where('user_id', $user_id)->latest()->paginate($limit);
    }

    public function findBySlug($slug)
    {
        return $this->model
                    ->with(['user', 'category', 'kota', 'supporters.user',
                        'rewards' => function ($q) {
                            return $q->orderBy('price', 'DESC');
                        },
                        'supporters' => function ($q) {
                            return $q->orderBy('id', 'desc');
                        },
                        'updates' => function ($q) {
                            return $q->orderBy('id', 'desc');
                        }
                        ])
                    ->where('slug', $slug)
                    ->firstOrFail();
    }

    public function findArtikelTerkait($id){
        return $this->model
                    ->with(['updates' => function ($q) {
                            return $q->orderBy('id', 'desc');
                        }])
                    ->where('id', $id)
                    ->firstOrFail();
    }

    public function isTitleExist($title)
    {
        $slug = Str::slug($title);
        $n = $this->model
                    ->where('title', $title)
                    ->orWhere('slug', $slug)
                    ->count();
        if($n >= 1) return true;
        else return false;
    }

    public function searchProject($keyword, $limit = 12)
    {
        return $this->model
                    ->where('status', 'active')
                    ->where('title', 'LIKE', "%$keyword%")
                    ->orWhere('summary', 'LIKE', "%$keyword%")
                    ->paginate($limit);
    }

    public function searchProjectBy($keyword = null, $category = null, $provinsi = null, $limit = 12, $sort = "")
    {
        $query = $this->model
                    ->leftJoin('category', 'category.id', '=', 'projects.category_id')
                    ->leftJoin('provinsi', 'provinsi.id', '=', 'projects.provinsi_id')
                    ->where( 'time_end', '>=' , date('Y-m-d G:i:s') )
                    ->where('is_fundraiser', 0)
                    ->where('projects.status', 'active');
        if (isset($keyword) && $keyword!=null && $keyword!="")
            $query = $query->where('title', 'LIKE', "%$keyword%");
        if (isset($category) && $category!=null && $category!="")
            $query = $query->where('category.category_name', '=', "$category");
        if (isset($provinsi) && $provinsi!=null && $provinsi!="")
            $query = $query->where('provinsi.provinsi_name', '=', "$provinsi");
        if(isset($sort) && $sort != "" && $sort == "terdanai")
            $query = $query->where( 'time_end', '<' , date('Y-m-d G:i:s') );
        if(isset($sort) && $sort != "" && $sort == "hampir")
            $query = $query->whereRaw('money_progress < money_target')->orderBy('money_progress', 'desc');
        if(isset($sort) && $sort != "" && $sort == "trending") {
            $subquery = Supporter::query()
                ->selectRaw('COUNT(supporters.id) as supporterCount')
                ->whereRaw('supporters.project_id = projects.id');
            $query = $query->selectRaw('*, ('.DB::raw($subquery->toSql()).') AS supporterCount')->orderBy('supporterCount', 'DESC');
        }
        // if (isset($keyword) && $keyword!=null && $keyword!="")
        //     $query = $query->orWhere('summary', 'LIKE', "%$keyword%");

        if (!$sort) {
            $query = $query->orderBy('projects.created_at', 'desc');
        }

        return $query->simplePaginate($limit);
    }

    /**
     * This will save supporter in pending status
     *
     * @param  Project $project
     * @param  array   $data
     * @return Model
     */
    public function saveSupporter(Project $project, array $data)
    {
        // generate unique_code
        $unique_code = rand(101, 999);

        $data = array_merge($data, [
            // 'notes' => '',
            'unique_code' => $unique_code,
            'payment_method' => $data['bank'],
            'status' => 'pending',
            'user_id' => auth()->user()->id,
            'money' => str_replace('.', '', str_replace('Rp', '', $data['money'])),
        ]);
        $project->supporters()->create($data);
        return $project;
    }

    public function saveSupporterNoAuth(Project $project, array $data)
    {
        // generate unique_code
        $unique_code = rand(101, 999);

        $data = array_merge($data, [
            // 'notes' => '',
            'unique_code' => $unique_code,
            'payment_method' => $data['bank'],
            'status' => 'pending',
            'user_id' => 0,
            'name'  => $data['name'],
            'phone'  => $data['phone'],
            'email'  => $data['email'],
            'referal'  => $data['referal'],
            'noauth'  => 1,
            'money' => str_replace('.', '', str_replace('Rp', '', $data['money'])),
        ]);
        $s = $project->supporters()->create($data);
        return $s;
    }

    /**
     * Accept Supporter Project
     *
     * @param  Project   $project
     * @param  Supporter $supporter
     * @return Model
     */
    public function acceptSupporter(Project $project, Supporter $supporter, $unique_code)
    {
        $supporter->setSuccess();

        // syncronize progress money
        $accepted_total = $this->totalProjectSupporterMoney($project);
        $project->money_progress = $accepted_total;
        $project->save();

        return true;
    }

    /**
     * Get Accepted Supporter Project
     *
     * @param  project_id
     * @return Model
     */
    public function getAcceptedSupporters($project_id)
    {
        return Supporter::where('project_id',$project_id)->where('status','accept')->get();
    }

    /**
     * Reject Supporter Project
     *
     * @param  Project   $project
     * @param  Supporter $supporter
     * @return Model
     */
    public function rejectSupporter(Project $project, Supporter $supporter)
    {
        $supporter->status = 'pending';
        $supporter->save();

        // syncronize progress
        $accepted_total = $this->totalProjectSupporterMoney($project);
        $project->money_progress = $accepted_total;
        $project->save();

        return true;
    }

    public function totalProjectSupporterMoney(Project $project)
    {
        return $project->supporters()->where('status', 'accept')->sum('money') + $project->supporters()->where('status', 'accept')->sum('unique_code');
    }

    public function createSubProject($data)
    {
        $parentProjectId = $data['fundraiser_project_id'];
        $parentProject = Project::find($parentProjectId);
        $project = $parentProject->replicate();
        $slug = empty($data['slug']) ? Str::slug($data['title']) : $data['slug'];

        DB::beginTransaction();

        try {
            $project->user_id = $data['user_id'];
            $project->title = $data['title'];
            $project->slug = $slug;
            $project->money_target = (int) str_replace('.', '', str_replace('Rp', '', $data['money_target']));
            $project->money_progress = 0;
            $project->is_fundraiser = 1;
            $project->fundraiser_project_id = $parentProjectId;
            $project->save();

            // Status cannot be updated in first save because model instance doesn't has ID
            // And it conflict with getStatusAttribute and return empty string
            // Even you assign a value into it
            $project->status = "active";
            $project->save();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

        DB::commit();
        
        return $project;
    }

    public function createProjectAndReward($data)
    {
        $model = $this->model;
        $this->_save($model, $data);
        return $model;
    }

    public function updateProjectAndReward($model, $data)
    {
        $this->_update($model, $data);
        return $model;
    }

    public function findDonasi($email, $code)
    {
        $user =  app('App\Models\User')->where('email', '=', $email)->firstOrFail();
        // return $user->id;

        return Supporter::where('user_id', '=', $user->id)->where('unique_code', $code)->firstOrFail();
    }

    public function withdraw($data)
    {
        DB::beginTransaction();

        $withdraw = NULL;

        try {
            $withdraw = ProjectWithdraw::create($data);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

        DB::commit();
        
        return $withdraw;
    }

    private function _save($model, $data)
    {
        DB::beginTransaction();
        try {
            $model->fill($data);
            $model->money_target = (integer) str_replace('.', '', str_replace('Rp', '', $data['money_target']));
            if(empty($data['slug'])){
                $model->slug = Str::slug($data['title']);
            }else{
                $model->slug = $data['slug'];
            }

            $video = str_replace('watch?v=', 'embed/', $data['video']);
            $video = explode('&', $video)[0];
            
            $model->video = $video;
            $model->video_type = 'youtube';
            if (isset($data['user_id']) && !empty($data['user_id'])) {
                $model->user_id = $data['user_id'];
            }else {
                $model->user_id = auth()->user()->id;
            }
            $model->status = !empty($model['status']) ? $model['status'] : 'pending';
            // $model->time_start = Carbon::now();
            // $model->time_end = Carbon::now()->addDays($data['duration']);
            $model->category_id = $data['category'];
            $model->provinsi_id = $data['province'];
            $model->kota_id = $data['city'];
            $model->time_start = date( 'Y-m-d H:i:s', strtotime( $data['startproject'] ) );
            $model->time_end = date( 'Y-m-d H:i:s', strtotime( $data['endproject'] ) );
            // $model->type_business = $data['type_business'];
            $model->save();

            if (!empty($data['rewards'])) {
                $rewardData = $data['rewards'];

                // get from price
                $total_reward = count($rewardData['price']);

                // refresh rewards first
                $model->rewards()->where('project_id', $model->id)->delete();

                for ($i = 0; $i < $total_reward; $i++) {
                    $model->rewards()->create([
                        'project_id' => $model->id,
                        'title' => '',
                        'price' => (integer) str_replace('.', '', str_replace('Rp', '', $rewardData['price'][$i])),
                        'content' => $rewardData['content'][$i],
                        'max_name_count' => $rewardData['max_name_count'][$i],
                        'cover' => $rewardData['cover'][$i],
                    ]);
                }
            }

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

        DB::commit();
        return $model;
    }

    private function _update($model, $data)
    {
        DB::beginTransaction();
        try {
            $model->fill($data);
            $model->money_target = (integer) str_replace('.', '', str_replace('Rp', '', $data['money_target']));
            
            $model->slug = $data['slug'];
            
            $video = str_replace('watch?v=', 'embed/', $data['video']);
            $video = explode('&', $video)[0];
            
            $model->video = $video;
            $model->video_type = 'youtube';
            $model->status = 'pending';
            $model->category_id = $data['category'];
            $model->provinsi_id = $data['province'];
            $model->kota_id = $data['city'];
            $model->time_start = date( 'Y-m-d H:i:s', strtotime( $data['startproject'] ) );
            $model->time_end = date( 'Y-m-d H:i:s', strtotime( $data['endproject'] ) );
            $model->save();

            if (!empty($data['rewards'])) {
                $rewards = $data['rewards'];
                $totalRewards = count($rewards['id']);
                $existingRewardIds = $model->rewards->pluck('id')->toArray();
                $deletedRewardIds = array_diff($existingRewardIds, $rewards['id']);

                if (count($deletedRewardIds)) {
                    $model->rewards()->whereIn('id', $deletedRewardIds)->delete();
                }

                $rewards = collect(range(0, $totalRewards - 1))->map(function($number) use ($rewards) {
                    return (object) [
                        'id' => $rewards['id'][$number],
                        'price' => $rewards['price'][$number],
                        'cover' => $rewards['cover'][$number],
                        'content' => $rewards['content'][$number],
                        'max_name_count' => $rewards['max_name_count'][$number],
                        'is_new' => $rewards['is_new'][$number],
                    ];
                });

                foreach ($rewards as $reward) {
                    $isNew = (int) $reward->is_new;

                    if ($isNew) {
                        $model->rewards()->create([
                            'project_id' => $model->id,
                            'title' => '',
                            'price' => (integer) str_replace('.', '', str_replace('Rp', '', $reward->price)),
                            'content' => $reward->content,
                            'max_name_count' => $reward->max_name_count,
                            'cover' => $reward->cover,
                        ]);
                    } else {
                        $existingReward = $model->rewards()->find($reward->id);

                        $existingReward->update([
                            'price' => (integer) str_replace('.', '', str_replace('Rp', '', $reward->price)),
                            'content' => $reward->content,
                            'max_name_count' => $reward->max_name_count,
                            'cover' => $reward->cover,
                        ]);
                    }
                }
            }

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

        DB::commit();
        return $model;
    }

}
