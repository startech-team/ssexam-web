<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
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
Route::get('/chart', function () {
    return view('chart');
});

Route::post('/export-chart', 'App\Http\Controllers\DashboardController@exportPdf');

Route::view('/', 'auth.login')->name('auth.login');

Route::get('/', function () {
    return redirect(route('logout'));
});

Route::get('logout', 'App\Http\Controllers\Auth\LoginController@logout');

Route::get('/termsAndPrivacyPolicy', function(){
    return view("terms.index");
});

/*Auth*/
Auth::routes();


Route::middleware('admin')->group(function () {
    /* Admin route*/
   // Route::get('/admin', 'App\Http\Controllers\HomeController@adminHome')->name('admin.index');
    Route::get('/admin/dashboard/detail/{acc_id}/{exam_id}', 'App\Http\Controllers\HomeController@dashboardDetail');
    Route::get('/admin/dashboard/search', 'App\Http\Controllers\HomeController@search');
    Route::get('/admin/dashboard/pdf/{acc_id}/{exam_id}', 'App\Http\Controllers\HomeController@pdf'); 
    Route::get('/admin/export-exam-pdf', 'App\Http\Controllers\DashboardController@exportPDF');
    Route::get('admin/generate-pdf', 'App\Http\Controllers\DashboardController@generatePDF');
    Route::get('/admin', 'App\Http\Controllers\DashboardController@index')->name('admin.dashboard');
    Route::get('/admin/groupPercent', 'App\Http\Controllers\DashboardController@groupPercent');
    Route::get('/admin/examPieChart/{exam_id}', 'App\Http\Controllers\DashboardController@examPieChart');
    Route::post('/admin/anatyticsByQuestion', 'App\Http\Controllers\DashboardController@anatyticByQuestion');
    Route::get('admin/exam/summary/{exam_id}', 'App\Http\Controllers\DashboardController@examSummary');
    Route::get('admin/dashboard/exam-list', 'App\Http\Controllers\DashboardController@examList');
    Route::get('/admin/examGroup', 'App\Http\Controllers\DashboardController@examGroup');

    /*group route*/
    Route::get('/admin/group', 'App\Http\Controllers\GroupController@index');
    Route::get('/admin/group/insert', 'App\Http\Controllers\GroupController@create');
    Route::post('/admin/group/store', 'App\Http\Controllers\GroupController@store');
    Route::get('/admin/group/edit/{id}', 'App\Http\Controllers\GroupController@edit');
    Route::post('/admin/group/update', 'App\Http\Controllers\GroupController@update');
    Route::post('/admin/group/delete/{id}', 'App\Http\Controllers\GroupController@destroy');
    Route::patch('/admin/group/delete/{id}', 'App\Http\Controllers\GroupController@destroy');

    /*account route*/
    Route::get('/admin/account', 'App\Http\Controllers\AccountController@index');
    Route::get('/admin/account/insert', 'App\Http\Controllers\AccountController@create');
    Route::get('/admin/account/edit/{id}', 'App\Http\Controllers\AccountController@edit');
    Route::post('/admin/account/store', 'App\Http\Controllers\AccountController@store');
    Route::post('/admin/account/update', 'App\Http\Controllers\AccountController@update');
    Route::post('/admin/account/delete/{id}', 'App\Http\Controllers\AccountController@destroy');
    Route::get('/admin/account/search', 'App\Http\Controllers\AccountController@search');
    Route::post('/admin/account/resetPwd/{id}', 'App\Http\Controllers\AccountController@resetPwd');
    Route::post('/admin/account/changeStatus/{id}', 'App\Http\Controllers\AccountController@changeStatus');

    /*exam route*/
    Route::post('/admin/exam/questionadd', 'App\Http\Controllers\ExamController@questionadd')->name("admin.questionadd");
    Route::post('/admin/exam/accountadd', 'App\Http\Controllers\ExamController@accountadd')->name("admin.accountadd");

    Route::get('/admin/exam',  'App\Http\Controllers\ExamController@index');
    Route::get('/admin/exam/insert', 'App\Http\Controllers\ExamController@create');
    Route::get('/admin/exam/edit/{id}', 'App\Http\Controllers\ExamController@edit');
    Route::post('/admin/exam/store', 'App\Http\Controllers\ExamController@store');
    Route::post('/admin/exam/update', 'App\Http\Controllers\ExamController@update');
    Route::post('/admin/exam/delete/{id}', 'App\Http\Controllers\ExamController@destroy');
    Route::post('/admin/reexam',  'App\Http\Controllers\ExamController@reexam');

    /*Question Type route*/
    Route::get('/admin/questionType', 'App\Http\Controllers\QuestionTypeController@index');
    Route::get('/admin/questionType/insert', 'App\Http\Controllers\QuestionTypeController@create');
    Route::post('/admin/questionType/store', 'App\Http\Controllers\QuestionTypeController@store');
    Route::get('/admin/questionType/edit/{id}', 'App\Http\Controllers\QuestionTypeController@edit');
    Route::post('/admin/questionType/update', 'App\Http\Controllers\QuestionTypeController@update');
    Route::post('/admin/questionType/delete/{id}', 'App\Http\Controllers\QuestionTypeController@destroy');


    /*Question route*/
    Route::get('/admin/question', 'App\Http\Controllers\QuestionController@index');
    Route::get('/admin/question/insert', 'App\Http\Controllers\QuestionController@create');
    Route::post('/admin/question/store', 'App\Http\Controllers\QuestionController@store');
    Route::get('/admin/question/edit/{question_id}', 'App\Http\Controllers\QuestionController@edit');
    Route::post('/admin/question/update', 'App\Http\Controllers\QuestionController@update');
    Route::post('/admin/question/delete/{id}', 'App\Http\Controllers\QuestionController@destroy');
    
    /*Category route*/
    Route::get('/admin/category', 'App\Http\Controllers\CategoryController@index');
    Route::delete('/admin/category/delete/{id}', 'App\Http\Controllers\CategoryController@destroy');
    Route::get('/admin/category/insert', 'App\Http\Controllers\CategoryController@create');
    Route::post('/admin/category/store', 'App\Http\Controllers\CategoryController@store');
    Route::get('/admin/category/edit/{id}','App\Http\Controllers\CategoryController@edit');
    Route::post('/admin/category/update/{id}','App\Http\Controllers\CategoryController@update');

    /* Study route */
    Route::get('/admin/study', 'App\Http\Controllers\StudyController@index');
    Route::get('/admin/study/insert', 'App\Http\Controllers\StudyController@create');
    Route::post('/admin/study/store', 'App\Http\Controllers\StudyController@store');
    Route::get('/admin/study/edit/{id}', 'App\Http\Controllers\StudyController@edit');
    Route::post('/admin/study/update', 'App\Http\Controllers\StudyController@update');
    Route::delete('/admin/study/delete/{id}', 'App\Http\Controllers\StudyController@destroy');
    

    /* Term route */
    Route::get('/admin/term', 'App\Http\Controllers\TermController@index');
    Route::get('/admin/term/insert','App\Http\Controllers\TermController@create');
    Route::post('/admin/term/store','App\Http\Controllers\TermController@store');
    Route::delete('/admin/term/delete/{id}', 'App\Http\Controllers\TermController@destroy');
    Route::get('/admin/term/edit/{term_id}','App\Http\Controllers\TermController@edit');
    Route::post('/admin/term/update/{term_id}','App\Http\Controllers\TermController@update');
    
    /* Change Password*/
    Route::get('/admin/changePassword', 'App\Http\Controllers\HomeController@changePassword');
    Route::post('/admin/changePasswordOk', 'App\Http\Controllers\HomeController@changePasswordOk');
});

/* User route*/
Route::get('/user', 'App\Http\Controllers\UserController@index');
Route::get('/user/exam-rule/{exam_id}', 'App\Http\Controllers\UserController@rule');
Route::get('/user/exam-detail/{exam_id}', 'App\Http\Controllers\UserController@detail');
Route::post('/user/exam-commit', 'App\Http\Controllers\UserController@examCommit');
Route::post('/user/exam-count-time', 'App\Http\Controllers\UserController@examCountTime');
/* Change Password*/
Route::get('/user/changePassword', 'App\Http\Controllers\UserController@changePassword');
Route::post('/user/changePasswordOk', 'App\Http\Controllers\UserController@changePasswordOk');
