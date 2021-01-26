<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('employee/login');
});

Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');


/**
 * 
 * Manager Views
 * 
 */
Route::view('/manager/login', 'manager/login')->middleware(['guest:manager','revalidate']);
Route::view('/manager/register', 'manager/register')->middleware(['guest:manager','revalidate']);
Route::view('/manager/index', 'manager/index')->middleware(['manager','revalidate']);
Route::view('/manager/employees', 'manager/employees')->middleware(['manager','revalidate']);
Route::view('/manager/jobtitles', 'manager/jobtitles')->middleware(['manager','revalidate']);
Route::view('/manager/objectives', 'manager/objectives')->middleware(['manager','revalidate']);
Route::view('/manager/forgotpassword','manager/forgotpassword')->middleware(['guest:manager','revalidate']);
Route::view('/manager/resetpassword/{link}', 'manager/resetpassword')->middleware(['guest:manager','revalidate']);

//update views
Route::get('/manager/jobtitles/update/{id}', 'ManagerController@showJobTitle')->middleware(['manager','revalidate']);
Route::get('/manager/objectives/update/{id}', 'ManagerController@showObjective')->middleware(['manager','revalidate']);
Route::get('/manager/objectives/deactivate/{id}', 'ManagerController@deactivateObjective')->middleware(['manager','revalidate']);
Route::get('/manager/objectives/activate/{id}', 'ManagerController@activateObjective')->middleware(['manager','revalidate']);
Route::get('/manager/employees/activate/{id}', 'ManagerController@activateAccount')->middleware(['manager','revalidate']);
Route::get('/manager/employees/deactivate/{id}', 'ManagerController@deactivateAccount')->middleware(['manager','revalidate']);


//manager operations
Route::post('/registerEmployee','ManagerController@registerEmployee');
Route::post('/loginManager','Auth\LoginController@managerLogin');
Route::post('/registerManager','Auth\RegisterController@createManager');
Route::post('/addJobTitle','ManagerController@addJobTitle')->middleware(['manager','revalidate']);
Route::post('/addObjective','ManagerController@addObjective')->middleware(['manager','revalidate']);
Route::post('/requestManagerPassword','Auth\ForgotPasswordController@managerPasswordResetRequest');
Route::post('/resetManagerPassword','Auth\ForgotPasswordController@resetManagerPassword');

//update operations
Route::post('/updateJobTitle/{id}','ManagerController@updateJobTitle')->middleware(['manager','revalidate']);
Route::post('/updateObjective/{id}','ManagerController@updateObjective')->middleware(['manager','revalidate']);

//retrieve data--Manager Role
Route::get('manager/index' , 'ManagerController@indexGetEvaluationData')->middleware(['manager','revalidate']);
Route::get('manager/employees','ManagerController@getEmployeesData')->middleware(['manager','revalidate']);
Route::get('manager/jobtitles','ManagerController@getJobTitleData')->middleware(['manager','revalidate']);
Route::get('manager/objectives','ManagerController@getObjectiveData')->middleware(['manager','revalidate']);


/**
 * 
 * Employee Views
 * 
 */
Route::view('/employee/login', 'employee/login')->middleware(['guest:employee','revalidate']);
Route::view('/employee/index', 'employee/index')->middleware(['employee','revalidate']);
Route::view('/employee/forgotpassword','employee/forgotpassword')->middleware(['guest:employee','revalidate']);
Route::view('/employee/resetpassword/{link}', 'employee/resetpassword')->middleware(['guest:employee','revalidate']);
Route::view('/setevaluationform','employee/setevaluationform')->middleware(['employee','revalidate']);
Route::view('/evaluateemployee/{id}','employee/evaluateemployee')->middleware(['employee','revalidate']);

// employee operations
Route::post('/loginEmployee','Auth\LoginController@employeeLogin');
Route::post('/requestEmployeePassword','Auth\ForgotPasswordController@employeePasswordResetRequest');
Route::post('/resetEmployeePassword','Auth\ForgotPasswordController@resetEmployeePassword');
Route::post('/addEvaluationForm','EvaluatorController@addForm')->middleware(['employee','revalidate']);
Route::post('/evaluate/{form_id}','EvaluatorController@evaluateEmployee')->middleware(['employee','revalidate']);

//retrieve data--Employee Role
Route::get('/setevaluationform','EvaluatorController@getPageData')->middleware(['employee','revalidate']);
Route::get('/employee/index','EvaluatorController@getIndexPageData')->middleware(['employee','revalidate']);
Route::get('/evaluateemployee/{id}','EvaluatorController@getEvaluateEmployeePageData')->middleware(['employee','revalidate']);


//Route::post('/logoutEmployee','Auth\LoginController@managerLogout');
Route::view('/evaluator/index', 'evaluator/index');

