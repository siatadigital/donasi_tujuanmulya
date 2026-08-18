<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\Project\ProjectRepository;
use Illuminate\Http\Request;
use Input;
use Redirect;
use Auth;
use Mail;
use App\Models\Category;
use App\Models\Provinsi;
use App\Models\User;
use App\Models\Update;
use App\Models\Project;
use App\Models\ProjectWithdraw;
use App\Models\PaymentMethodGroup;
use App\Models\Option;

class ProjectController extends Controller
{
    protected $project;

    public function __construct(ProjectRepository $project)
    {
        $this->project = $project;
        $this->middleware('auth', [
            'only' => [
                // 'getCreate', 'postCreate', 'getEdit', 'putEdit','getUpdate','postUpdate'
                'getEdit',
                'putEdit',
                'getUpdate',
                'postUpdate',
                'getFundraiser',
                'postFundraiser'
            ],
        ]);
    }

    public function getIndex(Request $request)
    {
        $keyword    = Input::get('keyword', '');
        $category   = Input::get('kategory', '');
        $location   = Input::get('lokasi', '');
        $sort       = Input::get('sort', '');

        $data = [
            'title'             => 'Explore Project',
            'project_popular'   => $this->project->getPopular(6),
            'projects'          => $this->project->searchProjectBy($keyword, $category, $location, 9, $sort),
            'category'          => Category::all(),
            'provinsi'          => Provinsi::all(),
            'sort'              => (object) [
                (object) [
                    'value' => 'trending',
                    'label' => trans('project.trending'),
                ],
                (object) [
                    'value' => 'hampir',
                    'label' => trans('project.berjalan'),
                ],
                (object) [
                    'value' => 'terdanai',
                    'label' => trans('project.selesai'),
                ],
            ],
            'keyword'           => $keyword,
            'selected_kategory' => $category,
            'selected_provinsi' => $location,
            'selected_sort'     => $sort,
        ];

        return view('contents.project.index', $data);
    }

    public function getSearch($keyword = null)
    {
        $data = [
            'title' => 'Search',
            'searched' => $this->project->searchProject($keyword, 9),
        ];
        return view('contents.project.search', $data);
    }

    public function getCreate(Request $request)
    {
        /*if($this->_isVerified() == false)
            return redirect()->route('user.getSetting', auth()->user()->username);*/

        $projects = Project::where('is_fundraiser', '!=', 1)->get();

        $data = [
            'projects' => $projects,
            'category' => Category::all(),
            'provinsi' => Provinsi::all(),
            'users' => User::all(),
            'title' => trans('create_project.title'),
            'short' => $request->all(),
        ];
        return view('contents.project.create', $data);
    }

    public function postCreate(Request $request)
    {
        /*if($this->_isVerified() == false)
            return redirect()->route('user.getSetting', auth()->user()->username);*/

        if ($request->has('fundraiser_project_id')) {
            $this->validate($request, [
                'title' => 'required',
                'money_target' => 'required',
            ]);

            // check is title and slug exist
            if ($this->project->isTitleExist($request['title']))
                return redirect()->back()->withInput(Input::all())->withErrors(['This title was exist.']);

            $parentProjectId = $request->fundraiser_project_id;

            if (Auth::guest()) {
                $dataUser = $request->only(['name', 'email', 'username', 'phone', 'password', 'password_confirmation']);
                $userEmailExists = User::where('email', $dataUser['email'])->first();
                if ($userEmailExists)
                    return redirect()->back()->withInput(Input::all())->withErrors(['The email user is exists.']);

                $userUsernameExists = User::where('username', $dataUser['username'])->first();
                if ($userUsernameExists)
                    return redirect()->back()->withInput(Input::all())->withErrors(['The username user is exists.']);

                $resultUser = app('UserRepository')->create([
                    'name' => $dataUser['name'],
                    'username' => $dataUser['username'],
                    'email' => $dataUser['email'],
                    'password' => bcrypt($dataUser['password']),
                ]);

                $dataProject = $request->except(['name', 'email', 'username', 'phone', 'password', 'password_confirmation']);
                $dataProject['user_id'] = $resultUser->id;
                $project = $this->project->createSubProject($dataProject);

                $this->_sendRegistrationMail($dataUser);
            } else {
                $isSuperAdmin = Auth::user()->is_superadmin == '1';
                $userId = $isSuperAdmin ? $request['user_id'] : Auth::user()->id;
                $dataProject = $request->all();
                $dataProject['user_id'] = $userId;

                $project = $this->project->createSubProject($dataProject);
            }
        } else {
            $this->validate($request, [
                'title' => 'required',
                'summary' => 'required',
                'content' => 'required',
                'cover' => 'required',
                'money_target' => 'required',
                'category' => 'required|numeric',
                'province' => 'required|numeric|not_in:0',
                'city' => 'required|numeric|not_in:0',
                'startproject' => 'required|date',
                'endproject' => 'required|date'
            ]);

            // check is title and slug exist
            if ($this->project->isTitleExist($request['title']))
                return redirect()->back()->withInput(Input::all())->withErrors(['This title was exist.']);
            // cek duration of date is correct
            if (strtotime($request['startproject']) >  strtotime($request['endproject']))
                return redirect()->back()->withInput(Input::all())->withErrors(['The date of end project must be greater than date of start.']);
            // check is provinsi and city was exist and truly related
            if (!app('App\Repositories\Kota\KotaRepository')->isRelatedProvinsi($request['province'], $request['city']))
                return redirect()->back()->withInput(Input::all())->withErrors(['This province and city not related.']);
            // cek category exist
            if (Category::where('id', $request['category'])->count() <= 0)
                return redirect()->back()->withInput(Input::all())->withErrors(['This category not exist.']);

            if (Auth::guest()) {
                $dataUser = $request->only(['name', 'email', 'username', 'phone', 'password', 'password_confirmation']);
                $userEmailExists = User::where('email', $dataUser['email'])->first();
                if ($userEmailExists)
                    return redirect()->back()->withInput(Input::all())->withErrors(['The email user is exists.']);

                $userUsernameExists = User::where('username', $dataUser['username'])->first();
                if ($userUsernameExists)
                    return redirect()->back()->withInput(Input::all())->withErrors(['The username user is exists.']);

                $resultUser = app('UserRepository')->create([
                    'name' => $dataUser['name'],
                    'username' => $dataUser['username'],
                    'email' => $dataUser['email'],
                    'password' => bcrypt($dataUser['password']),
                ]);

                $dataProject = $request->except(['name', 'email', 'username', 'phone', 'password', 'password_confirmation']);
                $dataProject['user_id'] = $resultUser->id;

                $project = $this->project->createProjectAndReward($dataProject);

                $this->_sendRegistrationMail($dataUser);
            } else {
                $dataProject = $request->all();
                if (Auth::user()->is_superadmin == '1') {
                    $dataProject['user_id'] = $request['user_id'];
                } else {
                    $dataProject['user_id'] = Auth::user()->id;
                }

                $project = $this->project->createProjectAndReward($dataProject);
            }
        }

        return redirect()->route('project.newGetShow', $project['slug']);
    }

    private function _sendRegistrationMail($dataUser)
    {
        $auth = $dataUser;
        $data = [
            'auth' => $auth,
            // 'token_url' => route('user.getVerified', Crypt::encrypt($auth['id'])),
        ];
        try {
            \Mail::queue('emails.welcome_message', $data, function ($message) use ($auth) {
                $message->to($auth['email'])->subject('Selamat datang di tujuanmulia.id');
            });
        } catch (\Exception $e) {
            // failed send email
        }
    }

    public function getUpdate(Request $request)
    {
        if ($this->_isVerified() == false)
            return redirect()->route('user.getSetting', auth()->user()->username);

        $data = [
            'title' => 'Update Info Terbaru Campaign!',
            'projects' => auth()->user()->activeProjects(),
            'project_id' => $request['project_id'],
        ];
        return view('contents.project.update', $data);
    }

    public function postUpdate(Request $request)
    {
        if ($this->_isVerified() == false)
            return redirect()->route('user.getSetting', auth()->user()->username);

        $this->validate($request, [
            'title' =>  'required',
            'description'   =>  'required',
            'project_id'    =>  'required|numeric|not_in:0'
        ]);

        // cek ownership of this project
        $project = $this->project->find($request['project_id']);
        if (auth()->user()->id != $project['user_id']) abort(404);

        $update = app('App\Repositories\Update\UpdateRepository')->createUpdate($request->all());

        $emails = $project->supporters->pluck('email')->toArray();

        $data = [
            'project' => $project,
            'title' => $request->title,
            'description' => $request->description,
        ];

        foreach ($emails as $email) {
            Mail::queue('emails.project-updated-info', $data, function ($message) use ($project, $email) {
                $message->to($email)->subject("Update Info Galang Dana \"{$project->title}\"");
            });
        }


        return redirect()->route('project.showUpdate', $update['id']);
    }

    public function getFundraiser($id)
    {
        if ($this->_isVerified() == false)
            return redirect()->route('user.getSetting', auth()->user()->username);

        $project = $this->project->find($id);
        $data = [
            'title' => 'Galang Dana Sebagai Fundraiser',
            'project' => $project,
        ];
        return view('contents.project.fundraiser', $data);
    }

    public function postFundraiser(Request $request, $id)
    {
        if ($this->_isVerified() == false)
            return redirect()->route('user.getSetting', auth()->user()->username);

        $this->validate($request, [
            'title' => 'required',
            'money_target' => 'required',
            'slug' => 'required',
        ]);

        // check is title and slug exist
        if ($this->project->isTitleExist($request['title']))
            return redirect()->back()->withInput(Input::all())->withErrors(['This title was exist.']);

        $parentProjectId = $id;

        $isSuperAdmin = Auth::user()->is_superadmin == '1';
        $userId = Auth::user()->id;
        $dataProject = $request->all();
        $dataProject['user_id'] = $userId;
        $dataProject['fundraiser_project_id'] = $id;

        $project = $this->project->createSubProject($dataProject);

        return redirect()->route('project.newGetShow', $project['slug']);
    }

    public function getEditUpdate($id)
    {
        if ($this->_isVerified() == false)
            return redirect()->route('user.getSetting', auth()->user()->username);

        $data = [
            'title' => 'Update Info Terbaru Campaign!',
            'projects' => auth()->user()->activeProjects(),
            'update' => Update::find($id),
        ];
        return view('contents.project.update_edit', $data);
    }

    public function postEditUpdate($id, Request $request)
    {
        if ($this->_isVerified() == false)
            return redirect()->route('user.getSetting', auth()->user()->username);

        $this->validate($request, [
            'title' =>  'required',
            'description'   =>  'required',
            'project_id'    =>  'required|numeric|not_in:0'
        ]);

        // cek ownership of this project
        $project = $this->project->find($request['project_id']);
        if (auth()->user()->id != $project['user_id']) abort(404);

        $updateFind = Update::find($id);
        $update = app('App\Repositories\Update\UpdateRepository')->editUpdate($updateFind, $request->all());
        return redirect()->route('project.showUpdate', $update['id']);
    }

    public function showUpdate($id)
    {
        $update = app('App\Repositories\Update\UpdateRepository')->findById($id);
        if (!isset($update)) abort(404);

        $project = $this->project->findArtikelTerkait($update->project_id);

        $data = [
            'title' => $update->title,
            'update' => $update,
            'project' => $project
        ];

        return view('contents.project.show-update', $data);
    }

    public function getEdit($slug)
    {
        if ($this->_isVerified() == false)
            return redirect()->route('user.getSetting', auth()->user()->username);

        $project = $this->project->findBySlug($slug);

        /*if (auth()->user()->id != $project['user_id']) {
        	abort(404);
        }*/

        $data = [
            'category' => Category::all(),
            'provinsi' => Provinsi::all(),
            'users' => User::all(),
            'title' => 'Edit - ' . $project->title,
            'project' => $project,
            'select_provinsi' => $project->provinsi_id,
            'select_kota' => $project->kota_id,
            'select_category' => $project->category_id,
            'date_start' => date('m/d/Y', strtotime($project->time_start)),
            'date_end' => date('m/d/Y', strtotime($project->time_end)),
            'time_start' => $project->time_start,
            'time_end' => $project->time_end,
            // 'type_business' => $project->type_business,
            // 'status_progress' => $project->status_progress,
        ];
        return view('contents.project.edit', $data);
    }

    public function putEdit($slug, Request $request)
    {
        if (!empty($request->edit_slug) && Project::where('slug', $request->edit_slug)->first()) {
            return redirect()->back()->withErrors('Custom Slug sudah ada, mohon untuk menggunakan yang lainnya !');
        }

        $req = $request->except('edit_slug');
        if (!empty($request->edit_slug)) {
            $req['slug'] = $request->edit_slug;
        }

        if ($this->_isVerified() == false)
            return redirect()->route('user.getSetting', auth()->user()->username);

        $project = $this->project->findBySlug($slug);

        /*if (auth()->user()->id != $project['user_id']) {
            abort(404);
        }*/

        $this->validate($request, [
            'title' => 'required',
            'summary' => 'required',
            'content' => 'required',
            // 'cover' => 'required',
            'category' => 'required|numeric|not_in:0',
            'province' => 'required|numeric|not_in:0',
            'city' => 'required|numeric|not_in:0',
            'money_target' => 'required',
            'startproject' => 'required|date',
            'endproject' => 'required|date'
        ]);

        // cek duration of date is correct
        if (strtotime($request['startproject']) >  strtotime($request['endproject']))
            return redirect()->back()->withErrors(['The date of end project must be greater than date of start.']);
        // check is provinsi and city was exist and truly related
        /*if ( ! app('App\Repositories\Kota\KotaRepository')->isRelatedProvinsi($request['province'], $request['city']) )
            return redirect()->back()->withErrors(['This province and city not related.']);*/
        // cek category exist
        /*if ( Category::where('id', $request['category'])->count() <= 0 )
            return redirect()->back()->withErrors( ['This category not exist.'] );*/

        $project = $this->project->updateProjectAndReward($project, $req);
        return redirect()->route('project.newGetShow', $project['slug']);
    }

    public function getShow($slug, Request $request)
    {
        if ($request->has('r')) {
            $code = $request->r;
            $me = auth()->user();

            $isCodeReferralValid = User::where('is_internal', TRUE)
                ->whereRaw("BINARY code_referral = '$code'")
                ->exists();

            if ($isCodeReferralValid) {
                if ($me) {
                    $isMyCode = $me->code_referral === $code;

                    if ($isMyCode) {
                        return redirect()->route('project.newGetShow', $slug);
                    }
                }
            } else {
                return redirect()->route('project.newGetShow', $slug);
            }
        }

        $project = $this->project->findBySlug($slug);
        $acceptedSupporters = $this->project->getAcceptedSupporters($project->id);
        $acceptedChildren = $project->children;

        $acceptedSupporters = $acceptedSupporters->map(function ($item) {
            $item->orders = json_decode($item->reward_id);

            return $item;
        });

        $selectedBySupporters = $acceptedSupporters
            ->pluck('orders')
            ->flatten()
            ->groupBy('id')
            ->map(function ($item) {
                return count($item);
            });

        $data = [
            'title' => $project->title,
            'project' => $project,
            'acceptedSupporters' => $acceptedSupporters,
            'acceptedChildren' => $acceptedChildren,
            'selectedBySupporters' => $selectedBySupporters,
            'payment_group' => PaymentMethodGroup::where('is_active', 1)->get(),
            'transaksi_city_input' => Option::where('type', 'string')->where('key', 'transaksi_city_input')->first()->value,
        ];

        return view('contents.project.show', $data);
    }

    public function getSupport($slug)
    {
        $project = $this->project->findBySlug($slug);
        if ($project['status'] != 'active') {
            return redirect()->back()->withMessages(['This project still in review process.']);
        }

        if (Auth::check()) {
            if ($this->_getAndCheckSupport($project->id)) {
                return redirect()->route('project.supportThankyou', $project['slug']);
            }
        }

        $data = [
            'title' => 'Support Project ' . $project['name'],
            'project' => $project,
        ];
        return view('contents.project.support', $data);
    }

    public function postSupport($slug, Request $request)
    {
        $project = $this->project->findBySlug($slug);

        $this->validate($request, [
            'money' => 'required',
            'reward_id' => 'required',
            'bank' => 'required',
        ]);

        // validate reward and money
        $reward = app('App\Models\Reward')->find($request->get('reward_id'));
        $money = str_replace('.', '', str_replace('Rp', '', $request->get('money')));

        //Minimal support 50000
        if ($money < 50000) {
            return redirect()->back()->withErrors(['Jumlah minimal transfer adalah 50.000']);
        }

        if ($reward) {
            if ($reward->price > $money) {
                return redirect()->back()->withErrors(['Jumlah dukungan anda tidak cukup untuk pilihan reward, pilih reward yang lain']);
            }
        }

        if (!Auth::check()) {
            $data = $this->project->saveSupporterNoAuth($project, $request->all());
            // return $data;
            return view('contents.project.support-no-auth')->withTitle($project['title'])->withSupport($data)->withSlug($project['slug']);
        }

        //save support to db
        $this->project->saveSupporter($project, $request->all());

        return redirect()->route('project.supportThankyou', $project['slug']);
    }

    public function supportThankyou($slug)
    {
        $project = $this->project->findBySlug($slug);
        $support = $this->_getAndCheckSupport($project->id);

        if (!$support) {
            abort(404);
        }

        $data = [
            'title' => 'Thankyou for supporting',
            'project' => $project,
            'support' => $support,
        ];
        return view('contents.project.support-step2', $data);
    }

    public function putConfirmPayment($slug)
    {
        $project = $this->project->findBySlug($slug);
        $support = app('App\Models\Supporter')->find(Input::get('support_id'));

        $support->notes = Input::get('notes');
        $support->has_confirm_payment = 1;
        $support->save();

        return redirect()->route('project.supportThankyou', $project['slug'])->withMessage([
            'title' => 'Thankyou for your confirm payment',
            'content' => 'We will check as soon as possible',
            'type' => 'success',
        ]);
    }

    private function _getAndCheckSupport($project_id)
    {
        return app('App\Models\Supporter')
            ->with('reward')
            ->where('project_id', $project_id)
            ->where('user_id', auth()->user()->id)
            ->first();
    }

    public function getCheckDonasi()
    {
        // $data = [
        //     'title' => 'Cek donasi',
        // ];
        // return view('contents.project.check-donasi', $data);
        return $this->project->findDonasi('admin@gmail.com', '1234');
    }

    public function postCheckDonasi(Request $request)
    {
        $this->validate($request, [
            'code'      => 'required|numeric|min:2',
            'email' => 'required|email'
        ]);

        return $request->input('email');
    }

    public function _isVerified()
    {
        $user = auth()->user();
        if ($user['is_verified'] != 1) {
            return false;
        } else {
            return true;
        }
    }

    public function getWithdraw($slug)
    {
        $userId = auth()->user() ? auth()->user()->id : NULL;
        $project = Project::where('slug', $slug)
            ->where('user_id', $userId)
            ->where('is_fundraiser', 0)
            ->where('status', '!=', 'pending')
            ->firstOrFail();

        $data = [
            'title' => 'Withdraw Penggalangan Dana',
            'project' => $project,
        ];

        return view('contents.project.withdraw', $data);
    }

    public function postWithdraw($slug, Request $request)
    {
        $this->validate($request, [
            'amount' => 'required',
            'account_bank' => 'required',
            'account_name' => 'required',
            'account_number' => 'required|numeric',
        ]);

        $project = Project::where('slug', $slug)
            ->where('status', '!=', 'pending')
            ->firstOrFail();

        $isPermitted = $request->amount <= $project->funds;

        if (!$isPermitted) {
            return response()->json([
                'message' => 'Nominal tidak boleh melebihi jumlah dana !',
                'data' => NULL,
            ], 400);
        }

        $req = $request->all();
        $req['project_id'] = $project->id;

        $withdraw = $this->project->withdraw($req);

        return response()->json([
            'message' => 'Proses withdraw berhasil !',
            'data' => number_format($project->funds),
        ]);
    }
}
