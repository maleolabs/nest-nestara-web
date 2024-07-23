<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Inspiring;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class LoginController extends Controller implements HasMiddleware
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
    protected $redirectTo = '/';

    public static function middleware()
    {
        return [
            new Middleware('guest', except: ['logout']),
            new Middleware('auth', only: ['logout'])
        ];
    }

    public function showLoginForm()
    {
        return view('auth.login', [
            'quote' => Inspiring::quote()
        ]);
    }

    protected function authenticated(Request $request, $user)
    {
        $role = $user->getRoleNames()[0];
        switch ($role) {
            case ADMIN_ROLE:
                return redirect()->route('admin.dashboard');
            case FRONT_OFFICE_ROLE:
                return redirect()->route('layanan.dashboard');
            case BACK_OFFICE_ROLE:
                return redirect()->route('pegawai.dashboard');
            case PEMOHON_ROLE:
                return redirect()->route('welcome');
            default:
                return redirect()->route('welcome');
        }
    }
    
}