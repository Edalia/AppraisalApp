<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Auth;
use App\JobTitle;
use App\Employee;
use App\Objective;
use App\EvaluationForm;
use App\EvaluatedObjective;
use App\Mail\SendMail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class EvaluatorController extends Controller
{
    public function getPageData(){
        $titleoptions= DB::table('jobtitle')
                                ->where('manager_id',Auth::guard('employee')->user()->manager_id)
                                ->get()
                                ->toArray();

        $objectives =DB::table('objective')
                         ->where('manager_id',Auth::guard('employee')->user()->manager_id)
                         ->where('isActive','1')
                         ->get()
                         ->toArray();

        $employees= DB::table('employee')
                                ->where('manager_id',Auth::guard('employee')->user()->manager_id)
                                ->where('jobtitle',Auth::guard('employee')->user()->jobtitle)
                                ->where('isEvaluator','0')
                                ->where('isSuspended','0')
                                ->get()
                                ->toArray();

        return view('employee.setevaluationform')
                        ->with('titleoptions',$titleoptions)
                        ->with('objectives',$objectives)
                        ->with('employees',$employees);
    }

    public function getIndexPageData(){
        $pendingForms = DB::table('evaluationform')
                            ->where('evaluator',Auth::guard('employee')->user()->id)
                            ->where('isSubmitted','0')
                            ->where('isArchived','0')
                            ->get()
                            ->toArray();

        $submittedForms = DB::table('evaluationform')
                            ->where('evaluator',Auth::guard('employee')->user()->id)
                            ->where('isSubmitted','1')
                            ->get()
                            ->toArray();

        $employees= DB::table('employee')
                                ->where('manager_id',Auth::guard('employee')->user()->manager_id)
                                ->get()
                                ->toArray();

        return view('employee.index')
                    ->with('pendingForms',$pendingForms)
                    ->with('submittedForms',$submittedForms)
                    ->with('employees',$employees);
    }

    public function addForm(Request $request){
        $validateFormData = $request->validate([
            'start_period' => 'required|date',
            'end_period' => 'required|date',
            'employee' => 'required',
        ]);
        

        $existingForm = DB::table('evaluationform')
                                ->where('employee',$request->employee)
                                ->where('isSubmitted','0')
                                ->where('isArchived','0')
                                ->get();
            
        if($existingForm->count()>0){
            Session::flash('alert-warning', 'There is a pending evaluation for this employee');
            return redirect('/setevaluationform');
        }else{
            $evaluationForm= new EvaluationForm();

            //for every employee whose form is being added
            $evaluationForm->evaluator = Auth::guard('employee')->user()->id;
            $evaluationForm->employee = $request->employee;
            $evaluationForm->start_period = $request->start_period;
            $evaluationForm->end_period = $request->end_period;
            $evaluationForm->evaluation_date = "2000-01-01";
            $evaluationForm->isSubmitted = "0";
            $evaluationForm->isArchived = "0";
            $evaluationForm->archived_date = "2000-01-01";
            $evaluationForm->comment = "N/A";
            $evaluationForm->final_rate = "0";
            $evaluationForm->save();

            if($evaluationForm->save()){
    
                if($request->filled('objectiveCheck')){
                    //$evaluatedObjective = new EvaluatedObjective();
                    
                     //for every objective selected
                    foreach($request->input('objectiveCheck') as $objectiveID){
                        
                        $evaluatedObjective_object[] = [
                            'evaluationform' => $evaluationForm->id,
                            'objective' => $objectiveID,
                            'status' => "2",
                            'rating' => "1",
                        ]; 
                    }                   
                    
                    $evaluated_objective = EvaluatedObjective::insert($evaluatedObjective_object);

                    if($evaluated_objective){
                        Session::flash('alert-success', 'The form was added successfully');
                        return redirect('/setevaluationform');
                    }else{
                        Session::flash('alert-danger', 'There was an error in adding the form');
                        return redirect('/setevaluationform');
                    }
                 }else{
                    Session::flash('alert-warning', 'Atleast one objective must be selected');
                    return redirect('/setevaluationform');
                 }
       
            }else{
                Session::flash('alert-danger', 'There was an error in adding the form');
                return redirect('/setevaluationform');
            }
        }

    }

    public function getEvaluateEmployeePageData(Request $request,$id){
        $forms = DB::table('evaluationform')
                        ->where('employee',$id)
                        ->where('isSubmitted','0')
                        ->where('isArchived','0')
                        ->get()
                        ->toArray();
        
        $employees = DB::table('employee')
                        ->where('id',$id)
                        ->get()
                        ->toArray();

        $jobtitles = DB::table('jobtitle')
                        ->where('manager_id',Auth::guard('employee')->user()->manager_id)
                        ->get()
                        ->toArray();
        
        $objectives =DB::table('objective')
                         ->where('manager_id',Auth::guard('employee')->user()->manager_id)
                         ->where('isActive','1')
                         ->get()
                         ->toArray();

        //always be one form, since page displays form based on id on url
        foreach($forms as $form){

            //select objectives to be assessed which belong to the single form
            $evaluatated_objectives = DB::table('evaluatated_objective')
                                        ->where('evaluationform',$form->id)
                                        ->get()
                                        ->toArray();
            
        }

        if(count($forms) < 1){
            return redirect("/employee/index");
        }
            return view('employee.evaluateemployee')
                    ->with('jobtitles',$jobtitles)
                    ->with('employees',$employees)
                    ->with('objectives',$objectives)
                    ->with('forms',$forms)
                    ->with('evaluatated_objectives',$evaluatated_objectives);

        //dd($employees);

    }

    public function evaluateEmployee(Request $request,$form_id){
        $validateEvaluationData = $request->validate([
             'comment' => 'required',
        ]);
        

        //get objectives to be evaluated
        $evaluatated_objectives = DB::table('evaluatated_objective')
                                    ->select('objective')
                                    ->where('evaluationform', $form_id)
                                    ->get()
                                    ->toArray();
           
        //for every single objective in the array
        foreach($evaluatated_objectives as $evaluatated_objective){

            /**
             * radio buttons are named dynamically i.e name:group1 = "group" + id of objective:"1"
             * based on element in evaluated_objective array (objective), get submited radio button value
             * i.e if objective = 1, get radio button value of radio button of name group1
             *     if objective = 2, get radio button value of radio button of name group2
             * 
             */

            /**
             * get submited value of radio button group , 
             * i.e hence if value of radiobutton group1 = 3, then 3 is the submitted rating
             * 
             */
            foreach($request->input("group".$evaluatated_objective->objective."") as $rating){
                /**
                 * create array whose values will be used to  update final table
                 */

                $evaluatated_objectives_array = array(
                    'rating' => $rating,
                );

            }
                
            //update table (evaluate listed objectives)
            $evaluate_employee = DB::table('evaluatated_objective')
                                    ->where('objective', $evaluatated_objective->objective)
                                    ->update($evaluatated_objectives_array);
    
        }

        //get submitted rating values
        $total_ratings = DB::table('evaluatated_objective')
                        ->select('rating')
                        ->where('evaluationform', $form_id)
                        ->get()
                        ->toArray();

        //used to get average rating
        $final_rate = 0;
        
        foreach($total_ratings as $total_rating)
        {
            $final_rate = $final_rate + $total_rating->rating;
        }
            //compute average rating (total over the number of objectives evaluated)
            echo $average_rate = $final_rate / count($evaluatated_objectives);

            $evaluation_form_array = array(
                'comment' => $request['comment'],
                'isSubmitted' => '1',
                'final_rate' => $average_rate,
                'evaluation_date' => date('y-m-d'),
                'updated_at' => date('y-m-d'),
            );
            
            $evaluation_comment = DB::table('evaluationform')
                                    ->where('id', $form_id)
                                    ->update($evaluation_form_array);
                                    
                                    
            if($evaluatated_objectives && $evaluation_comment){
                Session::flash('alert-success', 'The form has been submitted successfully');
                return redirect('/employee/index');
            }else{
                Session::flash('alert-danger', 'There was an error in submitting the form');
                return redirect('/employee/index');
            }
    }
}
