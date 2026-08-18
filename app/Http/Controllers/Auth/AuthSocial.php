<?php

namespace App\Http\Controllers\Auth;

use App\Repositories\Media\Uploader;
use Auth;
use Illuminate\Support\Str;
use Socialite;

trait AuthSocial
{
    /**
     * Redirect the user to the Facebook authentication page.
     *
     * @return Response
     */
    public function connectFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Obtain the user information from Facebook.
     *
     * @return Response
     */
    public function callbackFacebook()
    {
        $socialData = Socialite::driver('facebook')->user();

        if (!$this->_validateSocialData($socialData)) {
            return redirect()->route('auth.getLogin')->withErrors('sorry to connect with facebook you should allow your email permissions');
        }

        // save, authenticate, and redirect it
        return $this->_execute($socialData, 'facebook');
    }

    /**
     * Redirect the user to the Facebook authentication page.
     *
     * @return Response
     */
    public function connectTwitter()
    {
        return Socialite::driver('twitter')->redirect();
    }

    /**
     * Obtain the user information from Facebook.
     *
     * @return Response
     */
    public function callbackTwitter()
    {
        $socialData = Socialite::driver('twitter')->user();
        if (!$this->_validateSocialData($socialData, 'twitter')) {
            return redirect()->route('auth.getLogin')->withErrors('sorry to connect with twitter you should allow your email permissions');
        }

        // save, authenticate, and redirect it
        return $this->_execute($socialData, 'twitter');
    }

    private function _validateSocialData($socialData, $driver = 'facebook')
    {
        if ($driver == 'facebook') {
            if (empty($socialData->getEmail())) {

                // revoke user (TEMPORARY HARD CODED, loncing sek)
                $fb_url = 'https://graph.facebook.com/';
                $v = 'v2.4';
                $token = $socialData->token;
                (new \GuzzleHttp\Client)->delete($fb_url . $v . '/me/permissions?access_token=' . $token);

                return false;
            }
        }

        return true;
    }

    private function _execute($socialData, $provider_type = 'facebook')
    {
        // search database
        $user = app('App\Models\User')->where('email', $socialData->getEmail())->first();

        // insert to database if doesn't exists
        if (!$user) {
            $filename = str_random(20) . '.jpg';

            $data = [
                'name' => $socialData->getName(),
                'username' => $socialData->getNickname() ?: Str::slug($socialData->getName()),
                'email' => $socialData->getEmail(),
                'password' => null,
                'avatar' => $filename,
                'cover' => 'default.jpg',
                'bio' => 'I am awesome',
            ];

            if ($provider_type == 'facebook') {
                $data['facebook'] = 'https://www.facebook.com/' . $socialData->getId();
            } elseif ($provider_type == 'twitter') {
                $data['twitter'] = 'https://www.twitter.com/' . $socialData->getId();
            }

            $user = app('UserRepository')->create($data);

            $user->socials()->create([
                'user_id' => $user->id,
                'provider_type' => $provider_type,
                'provider_id' => $socialData->id,
                'access_token' => $socialData->token, // temporary short access_token that we store
            ]);

            // save avatar user
            if ($provider_type == 'facebook') {
                $file = $socialData->avatar . '&width=720&height=720';
            } elseif ($provider_type == 'twitter') {
                $file = $socialData->avatar_original;
            }

            (new Uploader)->saveImage($file, $filename);
        }

        // logining
        Auth::loginUsingId($user->id);

        // redirect
        return redirect()->intended($this->redirectPath());
    }
}
