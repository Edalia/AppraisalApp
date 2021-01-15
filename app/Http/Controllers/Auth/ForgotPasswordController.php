<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ManagerResetPassword;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;


    public function __construct()
    {
        $this->middleware('guest:manager');
        $this->middleware('guest:employee');
    }

    //manager reset
    public function managerPasswordResetRequest(Request $request)
    {
        $this->validate($request, [
            'email'   => 'required|email|exists:manager',
        ]);
        
        DB::table('password_resets')->insert([
            'email' => $request['email'],
            'token' => Str::random(40),
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        //Get the token just created above
            $tokenData = DB::table('password_resets')
                                ->where('email', $request['email'])
                                ->first();
        
        if ($this->sendManagerResetEmail($request['email'], $tokenData->token)) {
        
            Session::flash('alert-success', 'A reset link has been sent to your email address.');
        
            return redirect('manager/login');
        
        }else{
            Session::flash('alert-danger', 'An error occurred. Please try again.');
            return back()->withInput($request->only('email', 'remember'));
        }
    }

    private function sendManagerResetEmail($email, $token)
    {
        //Retrieve the user from the database
        $manager = DB::table('manager')
                    ->where('email', $email)
                    ->select('fname', 'email')
                    ->first();

        //Generate, the password reset link. The token generated is embedded in the link
        $link = config('base_url').'manager/resetpassword/'.$token;
        
        $data = array(
            "fname"=>$manager->fname,
            "link" => $link,
            "token"=>$token
        );

        try {
            Mail::send("mail/passwordreset", array('data' => $data), function($message) use ($email , $manager, $data){
                $message->to($email, $data['fname'])
                        ->subject('Password Reset Request');
                        
                $message->from('usermail@appraisalapp.com');
            });
               
            return true;

        } catch (Exception $e) {
            
            return false;
            
        }
    }
    
    public function resetManagerPassword(Request $request)
    {
        //Validate input
        $validrequest = $this->validate($request, [
            'email'   => 'required|email|exists:password_resets',
            'password' =>'required|confirmed|min:6',
        ]);
        
        if($validrequest){
            $password = $request['password'];

            $tokenData = DB::table('password_resets')
                            ->where('email', $request['email'])
                            ->first();

            $token = $tokenData->token;
            
            $updatepassword=array(
                        'password' => Hash::make($password),
                        'updated_at' => Carbon::now()->toDateTimeString()
            );


            $reset = DB::table('manager')
                            ->where('email', $request['email'])
                            ->update($updatepassword);
                        
            if($reset){
                $deletetoken = DB::table('password_resets')
                ->where('token', $token)
                ->delete();  

                Session::flash('alert-success', 'The password was reset successfully');
                return redirect("manager/login");
            }else{
                Session::flash('alert-danger', 'There was an error reseting the password');
                return redirect("manager/login");
            }
            
        }else{
            Session::flash('alert-danger', 'There was an error validating your request.');
            
            return redirect("manager/login");
        }

    }


    //empoyee reset
    public function employeePasswordResetRequest(Request $request){
        $this->validate($request, [
            'email'   => 'required|email|exists:employee',
        ]);
        
        DB::table('password_resets')->insert([
            'email' => $request['email'],
            'token' => Str::random(40),
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        //Get the token just created above
            $tokenData = DB::table('password_resets')
                                ->where('email', $request['email'])
                                ->first();
        
        if ($this->sendEmployeeResetEmail($request['email'], $tokenData->token)) {
        
            Session::flash('alert-success', 'A reset link has been sent to your email address.');
        
            return redirect('employee/login');
        
        }else{
            Session::flash('alert-danger', 'An error occurred. Please try again.');
            return back()->withInput($request->only('email', 'remember'));
        }
    }

    private function sendEmployeeResetEmail($email, $token){
        //Retrieve the user from the database
        $employee = DB::table('employee')
        ->where('email', $email)
        ->select('fname', 'email')
        ->first();

        //Generate, the password reset link. The token generated is embedded in the link
        $link = config('base_url').'employee/resetpassword/'.$token;

        $data = array(
        "fname"=>$employee->fname,
        "link" => $link,
        "token"=>$token
        );

        try {
        Mail::send("mail/passwordreset", array('data' => $data), function($message) use ($email , $employee, $data){
        $message->to($email, $data['fname'])
            ->subject('Password Reset Request');
            
        $message->from('usermail@appraisalapp.com');
        });

        return true;

        } catch (Exception $e) {

        return false;

        }
    }

    public function resetEmployeePassword(Request $request){
        $validrequest = $this->validate($request, [
            'email'   => 'required|email|exists:password_resets',
            'password' =>'required|confirmed|min:6',
        ]);
        
        if($validrequest){
            $password = $request['password'];

            $tokenData = DB::table('password_resets')
                            ->where('email', $request['email'])
                            ->first();

            $token = $tokenData->token;
            
            $updatepassword=array(
                'password' => Hash::make($password),
                'updated_at' => Carbon::now()->toDateTimeString()
            );


            $reset = DB::table('employee')
                            ->where('email', $request['email'])
                            ->update($updatepassword);
                        
            if($reset){
                $deletetoken = DB::table('password_resets')
                ->where('token', $token)
                ->delete();  

                Session::flash('alert-success', 'The password was reset successfully');
                return redirect("employee/login");
            }else{
                Session::flash('alert-danger', 'There was an error reseting the password');
                return redirect("employee/login");
            }
            
        }else{
            Session::flash('alert-danger', 'There was an error validating your request.');
            
            return redirect("employee/login");
        }
    }    
}
