<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Repositories\User\UserRepository;
use Input;
use Hash;

class UserController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $user;

    public function __construct(UserRepository $user)
    {
        $this->user = $user;
    }

    public function checkUsername()
    {
        $username = Input::get('username');

        if (!$this->user->getModel()->where('username', $username)->first() && strlen($username) > 3) {
            return [
                'status' => true,
                'message' => 'username available, and ready to use.',
            ];
        }

        return [
            'status' => false,
            'message' => 'username exists, you cannot using this username',
        ];
    }

    public function updateSetting()
    {
        $user = auth()->user();

        $rules = [
            'name' => 'required',
            'username' => 'required|unique:users,username,' . $user['id'],
            // 'facebook' => '',
            // 'twitter' => '',
            // 'instagram' => '',
            // 'youtube' => '',
            // 'soundcloud' => '',
        ];

        $input = Input::all();
        $v = \Validator::make($input, $rules);
        if ($v->fails()) {
            return ['status' => false, 'messages' => $v->errors()->getMessages()];
        }

		$input['facebook'] = $this->_getSegmentFromUrl(Input::get('facebook'));
		$input['twitter'] = $this->_getSegmentFromUrl(Input::get('twitter'));
		$input['instagram'] = $this->_getSegmentFromUrl(Input::get('instagram'));
		$input['youtube'] = $this->_getSegmentFromUrl(Input::get('youtube'));
		$input['soundcloud'] = $this->_getSegmentFromUrl(Input::get('soundcloud'));
        
        $this->user->update($user, $input);
        return ['status' => true, 'messages' => 'Your profile successfully updated'];
    }

    public function resetPassword()
    {
    	$input = Input::all();
		$user = auth()->user();
    	
    	$v = \Validator::make($input, [
    		'current_password' => 'required|min:6',
    		'password' => 'required|confirmed|min:6',
		]);

    	if (! Hash::check(Input::get('current_password'), $user['password'])) {
    		return ['status' => false, 'messages' => 'Your current password is wrong'];
    	}

		if ($v->fails()) {
			return ['status' => false, 'messages' => "Your new password not match!"];
		}

		$user->password = bcrypt(Input::get('password'));
		$user->save();

		return ['status' => true, 'messages' => 'Your password was successfully updated'];
    }


    private function _getSegmentFromUrl($url_string, $segment = 1)
    {
    	// if valid url
    	if (filter_var($url_string, FILTER_VALIDATE_URL)) {
			$parsed = parse_url($url_string);
			$path_parts = explode('/', $parsed['path']);
			return $path_parts[$segment];
		}

		// not valid url.
		return $url_string;
    }

}
