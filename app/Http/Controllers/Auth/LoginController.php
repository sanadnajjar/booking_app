<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


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

    use AuthenticatesUsers{

        login as protected webLogin;
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        if ($request->ajax())
        {
            $credentials = $request->only('email', 'password');

            try{
                if (!$token = \Tymon\JWTAuth\Facades\JWTAuth::attempt($credentials))
                {
                    return response()->json(['error' => 'invalid_credentials'], 401);
                }
            } catch (\Tymon\JWTAuth\Exceptions\JWTException $e){

                return response()->json(['error' => 'could_not_create_token'], 500);
            }

            $user = Auth::user();
            $role = $user->roles[0];

            return response()->json(compact('token', 'role'));
        }
        else
            return $this->webLogin($request);
    }
}
