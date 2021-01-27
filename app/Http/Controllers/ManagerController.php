<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Auth;
use App\JobTitle;
use App\Employee;
use App\Objective;
use Illuminate\Http\Request;
use App\Mail\SendMail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ManagerController extends Controller
{
    public function getEmployeesData(){
        $titleoptions= DB::table('jobtitle')
                                ->where('manager_id',Auth::guard('manager')->user()->id)
                                ->get()
                                ->toArray();

        $employees= DB::table('employee')
                                ->where('manager_id',Auth::guard('manager')->user()->id)
                                ->where('isEvaluator','0')
                                ->get();
        
        $evaluators=DB::table('employee')
                                ->where('manager_id',Auth::guard('manager')->user()->id)
                                ->where('isEvaluator','1')
                                ->get();

        return view('manager.employees')
                        ->with('titleoptions',$titleoptions)
                        ->with('employees',$employees)
                        ->with('evaluators',$evaluators);
    }

    public function suspendEmployeeAccount(Request $request, $id){
        $employee = Employee::find($id);
        $employee->update(['isSuspended' =>'1']);
    }
    public function activateEmployeeAccount(Request $request, $id){

        $employee = Employee::find($id);
        $employee->update(['isSuspended' =>'0']);
    }

    public function getJobTitleData(){
        $jobtitles= DB::table('jobtitle')
                                ->where('manager_id',Auth::guard('manager')->user()->id)
                                ->get();
        
        return view('manager.jobtitles')
                                ->with('jobtitles',$jobtitles);
    }

    public function getObjectiveData(){
        $objectives = DB::table('objective')
                        ->where('manager_id',Auth::guard('manager')->user()->id)
                        ->get()
                        ->toArray();
        
        $skills =DB::table('skill')
                        ->get()
                        ->toArray();

        $objectiveprioritys =DB::table('objectivepriority')
                        ->get()
                        ->toArray();
        
        $titleoptions= DB::table('jobtitle')
                        ->where('manager_id',Auth::guard('manager')->user()->id)
                        ->get()
                        ->toArray();

        return view('manager.objectives')
                    ->with('objectives',$objectives)
                    ->with('skills',$skills)
                    ->with('objectiveprioritys',$objectiveprioritys)
                    ->with('titleoptions',$titleoptions);
    }

    //reports
    public function indexGetEvaluationData(){
        $employees= DB::table('employee')
                                ->where('manager_id',Auth::guard('manager')->user()->id)
                                ->where('isEvaluator','0')
                                ->get()
                                ->toArray();

        $evaluators =   DB::table('employee')
                                ->where('manager_id',Auth::guard('manager')->user()->id)
                                ->where('isEvaluator','1')
                                ->get()
                                ->toArray();
        
        /* for each evaluator under this manager, 
           get their submitted evaluations
        */
        foreach($evaluators as $evaluator){

                    $submittedForms = DB::table('evaluationform')
                                        ->where('evaluator',$evaluator->id)
                                        ->where('isSubmitted','1')
                                        ->where('isArchived','0')
                                        ->get()
                                        ->toArray();

                    $archivedForms = DB::table('evaluationform')
                                        ->where('evaluator',$evaluator->id)
                                        ->where('isSubmitted','1')
                                        ->where('isArchived','1')
                                        ->get()
                                        ->toArray();
        }

        $formsnumber = count($submittedForms);

        
        
        return view('manager.index')
                    ->with('submittedForms',$submittedForms)
                    ->with('archivedForms',$archivedForms)
                    ->with('evaluators',$evaluators)
                    ->with('employees',$employees)
                    ->with('formsnumber',$formsnumber)
                    //to be used in graphs
                    ->with('evaluatorsNumeric',json_encode($evaluators,JSON_NUMERIC_CHECK))
                    ->with('evaluationFormsNumeric',json_encode(count($submittedForms),JSON_NUMERIC_CHECK));

    }


    /**
     * CRUD
     * 
     */

     //Insert functions
    public function registerEmployee(Request $request)
    {
        $validatedEmployeeData = $request->validate([
            'fname' => 'required|max:255',
            'lname' => 'required|max:255',
            'email' => 'required|unique:employee|max:255|email',
            'phone' => 'required|unique:employee|min:10|max:13', 
            'title'=>'required',
        ]);
        
        //check if employee is registered as an evaluator
        if ($request['evaluatorCheck']){
            $isEvaluator= "1";
        }else{
            $isEvaluator= "0";
        }

        $employee = Employee::create([
            'fname' => $request['fname'],
            'lname' => $request['lname'],
            'phone' => $request['phone'],
            'email' => $request['email'],
            'isEvaluator'=>$isEvaluator,
            'isSuspended'=>"0",
            'manager_id' =>Auth::guard('manager')->user()->id,
            'jobtitle'=>$request->title,
            'password' => Hash::make(Str::random(40)),
        ]);

        if($employee){
            Session::flash('alert-success', 'The employee has been registered successfully');
            return redirect('manager/employees');
        }else{
            Session::flash('alert-danger', 'There was an error in registering the employee');
            return redirect('manager/employees');
        } 
    }

    public function addJobTitle(Request $request)
    {
        $validateTitleData = $request->validate([
            'titlename' => 'required|string|max:255|unique:jobtitle',
        ]);

        $jobtitle = JobTitle::create([
            'manager_id' =>Auth::guard('manager')->user()->id,
            'titlename' => $request['titlename'],
        ]);

        if($jobtitle){
            Session::flash('alert-success', 'The Job Title was added successfully!');
            return redirect('manager/jobtitles');
        }else{
            Session::flash('alert-danger', 'There was an error in adding the Job Title');
            return redirect('manager/jobtitles');
        }
    }

    public function addObjective(Request $request){
        $validatedObjectiveData = $request->validate([
            'description' => 'required|max:255|unique:objective',
            'target' => 'required|max:255|unique:objective',
            'priority' => 'required',
            'skill' => 'required',
            'title'=>'required',
        ]);

        $objective = Objective::create([
            'description' => $request['description'],
            'manager_id' =>Auth::guard('manager')->user()->id,
            'jobtitle'=>$request->title,
            'isIndividual'=>'0',
            'isActive'=>'1',
            'target'=> $request['target'],
            'skill'=>$request->skill,
            'objective_priority'=>$request->priority,
        ]);
        
        if($objective){
            Session::flash('alert-success', 'The objective has been added');
            return redirect('manager/objectives');
        }else{
            Session::flash('alert-danger', 'There was an error in adding the objective');
            return redirect('manager/objectives');
        }

    }
    
    //Update Functions
    public function showJobTitle(Request $request,$id){
        $jobtitles = DB::table('jobtitle')
                                ->where('id',$id)
                                ->get();

            return view('manager.update.jobtitles')
                     ->with('jobtitles',$jobtitles);
    }

    public function updateJobTitle(Request $request,$id){
        $validateTitleData = $request->validate([
            'titlename' => 'required|string|max:255|unique:jobtitle',
        ]);
        
        
        $jobtitle_array = array(
            'titlename' => $request['titlename'],
            'updated_at' =>date('y-m-d'),
        );

        $update_jobtitle = DB::table('jobtitle')
                                    ->where('id', $id)
                                    ->update($jobtitle_array);
        
         if($update_jobtitle){
            Session::flash('alert-success', 'The Job Title was updated successfully!');
            return redirect('manager/jobtitles');
        }else{
            Session::flash('alert-danger', 'There was an error in updating the Job Title');
            return redirect('manager/jobtitles');
        }
    }

    public function showObjective(Request $request,$id){
        $objectives =DB::table('objective')
                    ->where('id',$id)
                    ->get()
                    ->toArray();

        $skills = DB::table('skill')
                    ->get()
                    ->toArray();

        $objectiveprioritys =DB::table('objectivepriority')
                            ->get()
                            ->toArray();

        $titleoptions= DB::table('jobtitle')
                ->where('manager_id',Auth::guard('manager')->user()->id)
                ->get()
                ->toArray();

        return view('manager.update.objectives')
            ->with('objectives',$objectives)
            ->with('skills',$skills)
            ->with('objectiveprioritys',$objectiveprioritys)
            ->with('titleoptions',$titleoptions);
    }

    public function updateObjective(Request $request,$id){
        $validatedObjectiveData = $request->validate([
            'description' => 'required|max:255|unique:objective',
            'target' => 'required|max:255|',
            'priority' => 'required',
            'skill' => 'required',
            'title'=>'required',
        ]);

        $objective_array = array(
            'description' => $request['description'],
            'target'=> $request['target'],
            'objective_priority'=>$request->priority,
            'skill'=>$request->skill,
            'jobtitle'=>$request->title,
            'updated_at'=>date('y-m-d'),   
        );

        //update table
        $update_objective = DB::table('objective')
                            ->where('id', $id)
                            ->update($objective_array);

        if($update_objective){
            Session::flash('alert-success', 'The Objective was updated successfully!');
            return redirect('manager/objectives');
        }else{
            Session::flash('alert-danger', 'There was an error in updating the Objective');
            return redirect('manager/objectives');
        }

    }

    public function activateObjective(Request $request,$id){
        $objective_array = array(
            'isActive' => '1',
            'updated_at' =>date('y-m-d'),
        ); 

        $activate_objective = DB::table('objective')
                                ->where('id', $id)
                                ->update($objective_array);

        if($activate_objective){
            Session::flash('alert-success', 'The objective was activated successfully!');
            return redirect('manager/objectives');
        }else{
            Session::flash('alert-danger', 'There was an error in activating the objective');
            return redirect('manager/objectives');  
        }    
    }

    public function deactivateObjective(Request $request,$id){
        $objective_array = array(
            'isActive' => '0',
            'updated_at' =>date('y-m-d'),
        ); 

        $deactivate_objective = DB::table('objective')
                                ->where('id', $id)
                                ->update($objective_array);

        if($deactivate_objective){
            Session::flash('alert-success', 'The objective was deactivated successfully!');
            return redirect('manager/objectives');
        }else{
            Session::flash('alert-danger', 'There was an error in deactivating the objective');
            return redirect('manager/objectives');
        }
    }

    public function activateAccount(Request $request,$id){
        $account_array = array(
            'isSuspended' => '0',
            'updated_at' =>date('y-m-d'),
        ); 

        $activate_account = DB::table('employee')
                                ->where('id', $id)
                                ->update($account_array);

        if($activate_account){
            Session::flash('alert-success', 'The user account was activated successfully!');
            return redirect('manager/employees');
        }else{
            Session::flash('alert-danger', 'There was an error in activating the user account');
            return redirect('manager/employees');  
        }    
    }
    public function deactivateAccount(Request $request,$id){
        $account_array = array(
            'isSuspended' => '1',
            'updated_at' =>date('y-m-d'),
        ); 

        $deactivate_account = DB::table('employee')
                                ->where('id', $id)
                                ->update($account_array);

        if($deactivate_account){
            Session::flash('alert-success', 'The user account was deactivated successfully!');
            return redirect('manager/employees');
        }else{
            Session::flash('alert-danger', 'There was an error in deactivating the user account');
            return redirect('manager/employees');  
        }    
    }


    //view evaluation
    public function viewEvaluation(Request $request,$id){
        
        $formdetails = DB::table('evaluationform')
                        ->where('isSubmitted','1')
                        ->where('id',$id)
                        ->get();

        foreach($formdetails as $formdetail){
            $employees = DB::table('employee')
                            ->where('id',$formdetail->employee)
                            ->get()
                            ->toArray();
        }

        $objectives = DB::table('objective')
                            ->where('manager_id',Auth::guard('manager')->user()->id)
                            ->get()
                            ->toArray();

        $evaluatated_objectives = DB::table('evaluatated_objective')
                            ->where('evaluationform',$id)
                            ->get()
                            ->toArray();

        $jobtitles = DB::table('jobtitle')
                        ->where('manager_id',Auth::guard('manager')->user()->id)
                        ->get()
                        ->toArray();

        if(count($formdetails) < 1){
            return redirect('manager/index');
        }
        else{
            return view('manager.viewevaluation')
                        ->with('formdetails',$formdetails)
                        ->with('employees',$employees)
                        ->with('objectives',$objectives)
                        ->with('jobtitles',$jobtitles)
                        ->with('evaluatated_objectives',$evaluatated_objectives );
        
        }
        
    }

    public function archiveEvaluation(Request $request,$id){

    }
}
