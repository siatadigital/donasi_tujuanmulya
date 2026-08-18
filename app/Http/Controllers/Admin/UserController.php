<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserPrivilege;
use App\Models\GroupPrivilege;
use App\Models\GroupPrivilegeDetail;
use App\Models\MenuAdmin;
use App\Models\DashboardItem;
use App\Models\UserDashboardPrivilege;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use Image;
use File;

class UserController extends Controller
{
    public function __construct()
    {
    }

    public function getLogin()
    {
        $data = [
            'title' => 'Login Prend',
        ];
        return view('admin::login', $data);
    }

    public function postLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email', 'password' => 'required',
        ]);

        $credentials = [
            'email' => $request->get('email'),
            'password' => $request->get('password'),
            'is_superadmin' => 1,
        ];

        if (!auth()->attempt($credentials, $request->get('remember'))) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['These credentials do not match our records.']);
        }

        return redirect()->intended(route('admin.page.getIndex'));
    }

    public function getLogout()
    {
        auth()->logout();

        return redirect()->route('admin.user.getLogin');
    }

    public function getIndex(Request $request)
    {
        $keyword = $request->get('keyword', '');

        $users = User::where('name', 'LIKE', "%{$keyword}%")
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $data = [
            'title' => 'Semua User',
            'users' => $users,
        ];

        return view('admin::contents.user.index', $data);
    }

    public function getShow($id)
    {
        $user = User::findOrFail($id);
        $data = [
            'title' => 'User Detail',
            'user' => $user,
        ];
        return view('admin::contents.user.show', $data);
    }

    public function getVerify(Request $request)
    {
        $keyword = $request->get('keyword', '');

        $users = app('App\Models\User')
            ->where('name', 'LIKE', "%{$keyword}%")
            ->where('is_superadmin', 0)
            ->where('is_verified', 1)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $data = [
            'title' => 'Verified Member',
            'users' => $users,
        ];
        return view('admin::contents.user.index', $data);
    }

    public function getMember(Request $request)
    {
        $keyword = $request->get('keyword', '');

        $users = app('App\Models\User')
            ->where('name', 'LIKE', "%{$keyword}%")
            ->where('is_superadmin', 0)
            ->where('is_verified', 0)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $data = [
            'title' => 'Member',
            'users' => $users,
        ];
        return view('admin::contents.user.index', $data);
    }

    public function getAdmin(Request $request)
    {
        $keyword = $request->get('keyword', '');

        $users = app('App\Models\User')
            ->where('name', 'LIKE', "%{$keyword}%")
            ->where('is_superadmin', 1)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $data = [
            'title' => 'Admin',
            'users' => $users,
        ];
        return view('admin::contents.user.index', $data);
    }

    public function createAdmin()
    {
        $groupPrivileges = GroupPrivilege::all();
        $menuAdmins = MenuAdmin::all();
        $dashboardItems = DashboardItem::all();

        do {
            $code = str_random(8);
            $isExists = User::whereRaw("BINARY code_referral = '$code'")->exists();
        } while ($isExists);

        $data = [
            'title' => 'Create Admin',
            'groupPrivileges' => $groupPrivileges,
            'menuAdmins' => $menuAdmins,
            'codeReferral' => $code,
            'dashboardItems' => $dashboardItems,
        ];
        return view('admin::contents.user.create-admin', $data);
    }

    public function storeAdmin(Request $request)
    {
        $req = $request->all();
        $isInternal = (int) $req['is_internal'];

        if (!$isInternal) {
            unset($req['code_referral']);
        }

        if ($request->hasFile('image')) {
            if ($request->file('image')->isValid()) {
                $destinationPath = 'media/images/medium/'; // upload path
                $extension = $request->file('image')->getClientOriginalExtension(); // getting image extension
                $fileName = rand(11111, 99999) . '.' . $extension; // renaming image
                $request->file('image')->move($destinationPath, $fileName); // uploading file to given path
                Image::make($destinationPath . $fileName)->resize(500, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->save($destinationPath . $fileName);
                $req['avatar'] = $fileName;
            }
        }

        $req['password'] = \Hash::make($req['password']);
        $req['is_artist'] = 1;
        $req['is_superadmin'] = 1;

        $user = User::create($req);
        $menuAdmins = MenuAdmin::all();
        $dashboardItems = DashboardItem::all();

        if ($request->has('menu_admin_ids')) {
            $menuAdminIds = collect($req['menu_admin_ids']);

            foreach ($menuAdmins as $menuAdmin) {
                $hasAccess = $menuAdminIds->contains($menuAdmin->id);

                UserPrivilege::create([
                    'user_id' => $user->id,
                    'menu_admin_id' => $menuAdmin->id,
                    'can_access' => $hasAccess,
                ]);
            }
        }

        if ($request->has('dashboard_item_ids')) {
            $dashboardItemIds = collect($req['dashboard_item_ids']);

            foreach ($dashboardItems as $dashboardItem) {
                $hasAccess = $dashboardItemIds->contains($dashboardItem->id);

                UserDashboardPrivilege::create([
                    'user_id' => $user->id,
                    'dashboard_item_id' => $dashboardItem->id,
                    'can_access' => $hasAccess,
                ]);
            }
        }

        return redirectMessage(
            route('admin.user.getAdmin'),
            ' successfully Created !!',
            '',
            'success'
        );
    }

    public function editAdmin($id)
    {
        $user = User::where('id', $id)->first();
        $groupPrivileges = GroupPrivilege::all();
        $menuAdmins = MenuAdmin::all();
        $dashboardItems = DashboardItem::all();

        do {
            $code = str_random(8);
            $isExists = User::whereRaw("BINARY code_referral = '$code'")->exists();
        } while ($isExists);

        $data = [
            'title' => 'Edit Admin',
            'data' => $user,
            'groupPrivileges' => $groupPrivileges,
            'menuAdmins' => $menuAdmins,
            'codeReferral' => $code,
            'dashboardItems' => $dashboardItems,
        ];

        return view('admin::contents.user.edit-admin', $data);
    }

    public function updateAdmin($id, Request $request)
    {
        $req = $request->except('_token', 'submit');
        $isInternal = (int) $req['is_internal'];

        if (!$isInternal) {
            unset($req['code_referral']);
        }

        if ($request->hasFile('image')) {
            if ($request->file('image')->isValid()) {
                $destinationPath = 'media/images/medium/'; // upload path
                $extension = $request->file('image')->getClientOriginalExtension(); // getting image extension
                $fileName = rand(11111, 99999) . '.' . $extension; // renaming image
                $request->file('image')->move($destinationPath, $fileName); // uploading file to given path
                Image::make($destinationPath . $fileName)->resize(500, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->save($destinationPath . $fileName);
                $req['avatar'] = $fileName;
                unset($req['image']);

                $result = User::find($id);
                if (!empty($result->avatar)) {
                    File::delete('media/images/medium/' . $result->avatar);
                }
            } else {
                unset($req['avatar']);
            }
        } else {
            unset($req['avatar']);
        }

        if (!empty($req['password'])) {
            $req['password'] = \Hash::make($req['password']);
        } else {
            unset($req['password']);
        }

        $user = User::find($id);
        $menuAdmins = MenuAdmin::all();
        $dashboardItems = DashboardItem::all();
        $result = $user->update($req);

        $user->privileges()->update(['can_access' => 0]);
        $user->dashboardPrivileges()->update(['can_access' => 0]);

        if ($request->has('menu_admin_ids')) {
            $menuAdminIds = collect($req['menu_admin_ids']);

            foreach ($menuAdmins as $menuAdmin) {
                $privilege = $user->privileges->where('menu_admin_id', $menuAdmin->id)->first();
                $hasAccess = $menuAdminIds->contains($menuAdmin->id);

                if ($privilege) {
                    $privilege->update(['can_access' => $hasAccess]);
                } else {
                    UserPrivilege::create([
                        'user_id' => $id,
                        'menu_admin_id' => $menuAdmin->id,
                        'can_access' => $hasAccess,
                    ]);
                }
            }
        }

        if ($request->has('dashboard_item_ids')) {
            $dashboardItemIds = collect($req['dashboard_item_ids']);

            foreach ($dashboardItems as $dashboardItem) {
                $privilege = $user->dashboardPrivileges->where('dashboard_item_id', $dashboardItem->id)->first();
                $hasAccess = $dashboardItemIds->contains($dashboardItem->id);

                if ($privilege) {
                    $privilege->update(['can_access' => $hasAccess]);
                } else {
                    UserDashboardPrivilege::create([
                        'user_id' => $id,
                        'dashboard_item_id' => $dashboardItem->id,
                        'can_access' => $hasAccess,
                    ]);
                }
            }
        }

        return redirectMessage(
            route('admin.user.getAdmin'),
            ' successfully Edited !!',
            '',
            'success'
        );
    }

    public function deleteAdmin($id)
    {
        $result = User::find($id);
        if (!empty($result->avatar)) {
            File::delete('media/images/medium/' . $result->avatar);
        }
        $result->delete();

        return redirectMessage(
            route('admin.user.getAdmin'),
            ' successfully Deleted !!',
            '',
            'success'
        );
    }

    public function getVerifyPending(Request $request)
    {
        $keyword = $request->get('keyword', '');

        $users = app('App\Models\User')
            ->where('name', 'LIKE', "%{$keyword}%")
            ->where('is_verified', 2)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $data = [
            'title' => 'Validation',
            'users' => $users,
        ];

        return view('admin::contents.user.validation-pending', $data);
    }

    public function putVerifyAccept($id)
    {
        $user = User::findOrFail($id);
        $user->is_verified = 1;
        $user->save();

        $this->_sendNotificationMail($user, 1);

        return redirectMessage(
            route('admin.user.getVerifyPending'),
            $user['name'] . ' successfully Confirmed !!',
            '',
            'success'
        );
    }

    public function putVerifyReject($id)
    {
        $user = User::findOrFail($id);
        $user->is_verified = 0;
        $user->save();

        $this->_sendNotificationMail($user, 2);

        return redirectMessage(
            route('admin.user.getVerifyPending'),
            $user['name'] . ' successfully Rejected !!',
            '',
            'success'
        );
    }

    public function putAsAdmin($id)
    {
        $user = User::findOrFail($id);
        $user->is_superadmin = !$user->is_superadmin;
        $user->save();
        $message = $user->is_superadmin == 1 ? "as admin" : "not as admin";

        return redirectMessage(
            'back',
            $user['name'] . ' successfully ' . $message . ' !!',
            '',
            'success'
        );
    }


    /*
    * Send notifikasi ke email user
    *
    * @params (data user, $status)
    */
    private function _sendNotificationMail($user, $status)
    {
        $data = [
            'user' => $user,
        ];
        try {
            if ($status == 1) { //send notif akun anda valid
                \Mail::queue('emails.verified_message', $data, function ($message) use ($user) {
                    $message->to($user['email'])->subject('Verifikasi Akun Berhasil');
                });
            } else if ($status == 2) { //send notif anda tidak valid
                \Mail::queue('emails.notverified_message', $data, function ($message) use ($user) {
                    $message->to($user['email'])->subject('Verifikasi Akun Gagal');
                });
            }
        } catch (\Exception $e) {
            // failed send email
        }
    }

    /**
     * method untuk mengirimkan notifikasi bahwa akun anda telah terverifikasi
     * @return [type] [description]
     */
    public function sendAllNotification2()
    {
        // mencari data user yang telah terverifikasi
        $userData = User::where('is_verified', 1)->get();

        foreach ($userData as $key => $user) {
            $data = [
                'user' => $user,
            ];
            try {
                \Mail::queue('emails.verified_message', $data, function ($message) use ($user) {
                    $message->to($user['email'])->subject('Verifikasi Akun Berhasil');
                });
            } catch (\Exception $e) {
                // failed send email
            }
            echo "hello " . $user['email'] . "<br>";
        }
    }

    /**
     * untuk method send email "Selamat Datang"
     */
    public function sendAllNotification()
    {
        $userData = User::all();

        foreach ($userData as $key => $auth) {
            $data = [
                'auth' => $auth,
            ];
            try {
                \Mail::queue('emails.welcome_message', $data, function ($message) use ($auth) {
                    $message->to($auth['email'])->subject('Selamat datang di tujuanmulia.id');
                });
            } catch (\Exception $e) {
                // failed send email
            }
            echo "hello " . $auth['email'] . "<br>";
        }
    }
}
