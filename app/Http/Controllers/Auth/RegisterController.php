<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use App\Manager;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    //protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
        $this->middleware('guest:manager');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    //protected function validator(array $data)
    //{
    //    return Validator::make($data, [
    //        'name' => ['required', 'string', 'max:255'],
    //        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
    //        'password' => ['required', 'string', 'min:8', 'confirmed'],
    //    ]);
    //}

    public function createManager(Request $request)
    {
        $validatedData = $request->validate([
            'fname' => 'required|max:255',
            'lname' => 'required|max:255',
            'email' => 'required|unique:manager|max:255|email',
            'phone' => 'required|unique:manager|min:10|max:13', 
            'password' =>'required|confirmed|min:6',
        ]);
        
        $manager = Manager::create([
            'fname' => $request['fname'],
            'lname' => $request['lname'],
            'phone' => $request['phone'],
            'isSuspended'=>"0",
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);

        if($manager){
            Session::flash('alert-success', 'Your account has been created successfully.Proceed to login.');
            return redirect('manager/login');
        }else{
            Session::flash('alert-danger', 'There was an error in creating your account');
            return redirect('manager/register');
        } 
    }
}
