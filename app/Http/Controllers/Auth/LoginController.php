<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Auth;

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
        $this->middleware('guest:manager')->except('logout');
        $this->middleware('guest:employee')->except('logout');
    }

    public function managerLogin(Request $request)
    {
        $this->validate($request, [
            'email'   => 'required|email|exists:manager',
            'password' => 'required|min:6'
        ]);
            
        if (Auth::guard('manager')->attempt(['isSuspended' => "0",'email' => $request->email, 'password' => $request->password], $request->get('remember'))) {

            return redirect()->intended('manager/index');
        }else{
            Session::flash('alert-danger', 'Invalid Credentials');
            return redirect('manager/login')->withInput($request->only('email', 'remember'));
        
        }

    }
    
    public function employeeLogin(Request $request)
    {
        $this->validate($request, [
            'email'   => 'required|email|exists:employee',
            'password' => 'required|min:6'
        ]);

        if (Auth::guard('employee')->attempt(['isSuspended' => "0",'email' => $request->email,'password' => $request->password], $request->get('remember'))) {

            return redirect()->intended('employee/index');
        }else{
            Session::flash('alert-danger', 'Invalid Credentials');
            return back()->withInput($request->only('email', 'remember'));
        }
    }
}
