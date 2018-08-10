<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Login via proxy request to auth server
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function loginProxy(Request $request)
    {
        $this->validateLogin($request);

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $client = new Client([
            'verify' => false
        ]);

        $response = $client->post(config('app.url').'/oauth/token', [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => config('auth.oauth.password.client_id'),
                'client_secret' => config('auth.oauth.password.client_secret'),
                'username' => $request->email,
                'password' => $request->password,
                'scope' => '*'
            ]
        ]);

        if ($response->getStatusCode() === 200) {
            $this->clearLoginAttempts($request);

           return response($response->getBody()->getContents(), $response->getStatusCode());
        }

        $this->incrementLoginAttempts($request);

        return response($response->getBody()->getContents(), $response->getStatusCode());
    }
}
