<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Auth;
use Crypt;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Validator;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    protected $redirectTo = '/';
    protected $redirectPath = '/';
    protected $redirectAfterLogout = '/auth/login';

    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
     */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins, AuthSocial;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        // $blacklist = implode(config('web.username_blacklist'), ',');
        return Validator::make($data, [
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return app('UserRepository')->create([
            'email' => $data['email'],
            'username' => explode('@', $data['email'])[0] . '' . rand(999, 9999),
            'password' => bcrypt($data['password']),
        ]);
    }

    public function getLogin()
    {
        return view('contents.auth.login');
    }

    /**
     * this method used for validation handled by trait class
     * @see AuthenticatesUsers.php
     *
     * @return string
     */
    protected function loginUsername()
    {
        return 'user';
    }

    /**
     * Allow user login with their email and username
     *
     * @param  Request $request
     * @return array
     */
    protected function getCredentials(Request $request)
    {
        $credentials = ['password' => $request->get('password')];

        $user = $request->get('user');
        if (filter_var($user, FILTER_VALIDATE_EMAIL)) {
            // user login using email
            $credentials['email'] = $user;
        } else {
            // user login using username
            $credentials['username'] = $user;
        }

        return $credentials;
    }

    /**
     * Get register page
     *
     * @return view
     */
    public function getRegister()
    {
        return view('contents.auth.register');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postRegister(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            if ($redirectTo = $request->get('redirectTo')) {
                return redirect()->to($redirectTo)
                    ->withInput($request->input())
                    ->withErrors($this->formatValidationErrors($validator), $this->errorBag());
            }
            $this->throwValidationException(
                $request,
                $validator
            );
        }

        Auth::login($this->create($request->all()));

        $this->_sendRegistrationMail();

        return redirect($this->redirectPath());
    }

    private function _sendRegistrationMail()
    {
        $auth = auth()->user();
        $data = [
            'auth' => $auth,
            // 'token_url' => route('user.getVerified', Crypt::encrypt($auth['id'])),
        ];
        try {
            \Mail::send('emails.welcome_message', $data, function ($message) use ($auth) {
                $message->to($auth['email'])->subject('Selamat datang di yukdonasi.org');
            });
        } catch (\Exception $e) {
            // failed send email
        }
    }

    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from provider.  Check if the user already exists in our
     * database by looking up their provider_id in the database.
     * If the user exists, log them in. Otherwise, create a new user then log them in. After that
     * redirect them to the authenticated users homepage.
     *
     * @return Response
     */
    public function handleProviderCallback($provider)
    {
        $user = Socialite::driver($provider)->user();
        if (User::where('email', $user->email)->whereNull('provider_id')->first()) {
            return redirect()->back()->withErrors(['Your email is already registered with our system']);
        }
        $authUser = $this->findOrCreateUser($user, $provider);
        Auth::login($authUser, true);
        return redirect('/');
    }

    /**
     * If a user has registered before using social auth, return the user
     * else, create a new user object.
     * @param  $user Socialite user object
     * @param $provider Social auth provider
     * @return  User
     */
    public function findOrCreateUser($user, $provider)
    {
        $authUser = User::where('provider_id', $user->id)->first();
        if ($authUser) {
            return $authUser;
        } else {
            $data = User::create([
                'name'     => $user->name,
                'email'    => !empty($user->email) ? $user->email : '',
                'username' => Str::slug($user->name) . '' . rand(999, 9999),
                'provider' => $provider,
                'provider_id' => $user->id
            ]);
            return $data;
        }
    }
}
