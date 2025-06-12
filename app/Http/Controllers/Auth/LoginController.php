<?php

namespace App\Http\Controllers\Auth;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
//        $this->middleware('auth')->only('logout');
    }
    protected function authenticated(Request $request, $user)
    {
        if ($user->status == 'active') {
        if ($user->role == User::ADMIN || $user->role == User::SUB_ADMIN) {

                return redirect()->route('admin_dashboard');

        }else if($user->role == User::SELLER){
            return redirect()->route('seller_dashboard');
        }else if($user->role == User::LOGISTIC_COMPANY){
            return redirect()->route('logistic_company_dashboard');
        }else if($user->role == User::DRIVER){
            return redirect()->route('driver_dashboard');
        }else{
            return redirect('login');
        }
        } else {
            Auth::logout();
        }
    }
}
