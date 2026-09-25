<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void$field
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Authenticate with an email, username, or student registration number.
     */
    protected function attemptLogin(Request $request)
    {
        $identity = $request->input('identity');
        $user = User::where(function ($query) use ($identity) {
            $query->where('email', $identity)
                ->orWhere('username', $identity);
        })->first();

        if (! $user) {
            $user = User::whereHas('student_record', function ($query) use ($identity) {
                $query->where('adm_no', $identity);
            })->first();
        }

        return $user && $this->guard()->attempt(
            ['id' => $user->id, 'password' => $request->input('password')],
            $request->filled('remember')
        );
    }
    public function username()
    {
        return 'identity';
    }
}
