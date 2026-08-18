<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\User\UserRepository;
use Crypt;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Donation;
use App\Models\Supporter;
use App\Models\Zakat;
use App\Models\Provinsi;

class UserController extends Controller
{
    protected $user;

    public function __construct(UserRepository $user)
    {
        $this->user = $user;
        $this->middleware('auth', [
            'only' => [
                'getSetting', 'postSetting',
            ],
        ]);
    }

    public function getIndex()
    {
        $users = $this->user->getAll(18);
        $data = [
            'title' => 'Semua User',
            'users' => $users,
        ];
        return view('contents.user.index', $data);
    }

    public function getShow($username)
    {
        $user = $this->user->findByUsername($username);
        $data = [
            'title' => $user['name'],
            'user' => $user,
        ];
        return view('contents.user.show', $data);
    }

    public function getProjects($username)
    {
        $user = $this->user->findByUsername($username);

        $user_projects = app('ProjectRepository')->getAllPaginateByUser($user['id'], 10);

        $data = [
            'title' => '',
            'user' => $user,
            'projects' => $user_projects,
        ];
        return view('contents.user.project', $data);
    }

    public function getSupport($username)
    {
        $user = $this->user->findByUsername($username);

        $my_own = true;
        if (!isset(auth()->user()->id) || auth()->user()->id != $user['id']) {
            $my_own = false;
        }

        // $user_supporting_project = $this->user->getSupportingProject($user, 10);
        // return $user_supporting_project;

        $supportings = app('App\Models\Supporter')->with(['user', 'project', 'reward'])
                                                  ->where('user_id', $user['id'])
                                                  ->orderBy('id', 'desc')
                                                  ->paginate(10);
        // return $supportings;

        $data = [
            'title'         => 'Supporting',
            'user'          => $user,
            'supportings'   => $supportings,
            'my_own'        => $my_own
            // 'supportings' => $user_supporting_project,
        ];
        return view('contents.user.support', $data);
    }

    public function getSetting($username)
    {
        $user = $this->user->findByUsername($username);

        if (auth()->user()->id != $user['id']) {
            return abort(404);
        }

        $data = [
            'title' => 'Basic Information',
            'user' => $user,
        ];
        return view('contents.user.setting', $data);
    }

    public function getSettingProfile($username)
    {
        $user = $this->user->findByUsername($username);

        if (auth()->user()->id != $user['id']) {
            return abort(404);
        }

        $data = [
            'title' => 'Profile Setting',
            'user' => $user,
        ];
        return view('contents.user.setting-profile', $data);
    }

    public function getSettingSocial($username)
    {
        $user = $this->user->findByUsername($username);

        if (auth()->user()->id != $user['id']) {
            return abort(404);
        }

        $data = [
            'title' => 'Social Account',
            'user' => $user,
        ];
        return view('contents.user.setting-social', $data);
    }

    public function getSettingSecurity($username)
    {
        $user = $this->user->findByUsername($username);

        if (auth()->user()->id != $user['id']) {
            return abort(404);
        }

        $data = [
            'title' => 'Security Setting',
            'user' => $user,
        ];
        return view('contents.user.setting-security', $data);
    }

    public function putSetting($username, Request $request)
    {
        $req = $request->except('_token', 'files');
        $user = $this->user->findByUsername($username);

        if (!empty($req['current_password']) and !empty($req['password']) and !empty($req['password_confirmation'])) {
          if ($req['password'] != $req['password_confirmation']) {
            unset($req['current_password']);
            unset($req['password']);
            unset($req['password_confirmation']);
          }
        }else {
          unset($req['current_password']);
          unset($req['password']);
          unset($req['password_confirmation']);
        }

        if (auth()->user()->id != $user['id']) {
            return abort(404);
        }
        $result = User::where('id', $user['id'])->update($req);

        return redirect()->back()->withInput();
    }

    public function getSearch($keyword = null)
    {
        $data = [
            'title' => 'Search',
            'searched' => $this->user->searchArtist($keyword),
        ];
        return view('contents.user.search', $data);
    }

    public function getVerified($encrypted)
    {
        try {
        	$auth = auth()->user();
            $decrypt_user_id = Crypt::decrypt($encrypted);
            if ($decrypt_user_id != $auth->id) {
                throw new \Exception("Error Processing Request", 1);
            }
        } catch (\Exception $e) {
            return abort(404);
        }

        $auth->is_verified = 1;
        $auth->save();

        return redirectMessage(
            route('user.getShow', $auth['username']),
            'Congratulations ! Your account was verified right now',
            null,
            'success'
        );
    }

    public function getValidate($username)
    {
        if( auth()->user()->is_verified != 0 )
            return redirect()->route('user.getSetting', auth()->user()->username);

        $data['user'] = User::where('id',auth()->user()->id)->first();
        $data['provinsi'] = Provinsi::all();

        return view('contents.user.validation',$data);
    }

    public function postValidate(Request $request, $username)
    {
        if( auth()->user()->is_verified != 0 )
            return redirect()->route('user.getSetting', auth()->user()->username);

        $this->validate($request, [
                'fotoktp' => 'required',
            ]);

        $this->user->saveFotoKtp( $username, $request->except('select_province','select_city'), 2 ); // 2 is verify status of waiting admin validation
        return redirect()->route('user.getSetting', auth()->user()->username);
    }

    public function getReportAffiliate(Request $request)
    {
        if (!auth()->user()->is_internal) {
            return redirect()->back();
        }

        $referralCode = auth()->user()->code_referral;

        $supporterTerakhirSum = Supporter::where('status', 'accept')->where('code_referral', $referralCode);
        $donaturTerakhirSum = Donation::where('status', 'success')->where('code_referral', $referralCode);
        $zakatTerakhirSum = Zakat::where('status', 'success')->where('code_referral', $referralCode);

        $supporterTerakhirList = Supporter::where('status', 'accept')->where('code_referral', $referralCode);
        $donaturTerakhirList = Donation::where('status', 'success')->where('code_referral', $referralCode);
        $zakatTerakhirList = Zakat::where('status', 'success')->where('code_referral', $referralCode);

        if ($request->has('from_date') && $request->has('to_date')) {
            $fromDate = date('Y-m-d H:i:s', strtotime($request->from_date . ' 00:00:00'));
            $toDate = date('Y-m-d H:i:s', strtotime($request->to_date . ' 23:59:00'));

            $supporterTerakhirSum = $supporterTerakhirSum->whereBetween('created_at', [$fromDate, $toDate]);
            $donaturTerakhirSum = $donaturTerakhirSum->whereBetween('created_at', [$fromDate, $toDate]);
            $zakatTerakhirSum = $zakatTerakhirSum->whereBetween('created_at', [$fromDate, $toDate]);

            $supporterTerakhirList = $supporterTerakhirList->whereBetween('created_at', [$fromDate, $toDate]);
            $donaturTerakhirList = $donaturTerakhirList->whereBetween('created_at', [$fromDate, $toDate]);
            $zakatTerakhirList = $zakatTerakhirList->whereBetween('created_at', [$fromDate, $toDate]);
        }

        $supporterTerakhirSum = $supporterTerakhirSum->orderBy('id', 'DESC')->sum('money');
        $donaturTerakhirSum = $donaturTerakhirSum->orderBy('id', 'DESC')->sum('amount');
        $zakatTerakhirSum = $zakatTerakhirSum->orderBy('id', 'DESC')->sum('amount');

        $supporterTerakhirList = $supporterTerakhirList->orderBy('id', 'DESC')->get();
        $donaturTerakhirList = $donaturTerakhirList->orderBy('id', 'DESC')->get();
        $zakatTerakhirList = $zakatTerakhirList->orderBy('id', 'DESC')->get();

        $data = [
            'title' => 'Laporan Affiliate',
            'supporterTerakhirSum' => $supporterTerakhirSum,
            'donaturTerakhirSum' => $donaturTerakhirSum,
            'zakatTerakhirSum' => $zakatTerakhirSum,
            'supporterTerakhirList' => $supporterTerakhirList,
            'donaturTerakhirList' => $donaturTerakhirList,
            'zakatTerakhirList' => $zakatTerakhirList,
        ];

        return view('contents.user.report_affiliate', $data);
    }
}
