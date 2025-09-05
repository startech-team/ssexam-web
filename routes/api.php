<?php

use App\Http\Controllers\api\CategoryController;
use App\Http\Controllers\api\UsersController;
use App\Http\Controllers\api\StudysController;
use App\Http\Controllers\api\TermsController;
use App\Http\Controllers\api\ExamController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/* User route*/
Route::post('login', [UsersController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [UsersController::class, 'logout']);
    Route::get('exams', [ExamController::class, 'exams']);
    Route::get('exams/start', [ExamController::class, 'exams_start']);
    Route::post('exams/update', [ExamController::class, 'exams_update']);
    Route::post('exams/exam_count_time', [ExamController::class,'exam_count_time']);
    /** Account Delete */
    Route::get('destroy', [UsersController::class, 'destroy']);
    Route::get('profile', [UsersController::class, 'profile']);
    Route::post('profile_update', [UsersController::class,'profile_update']);
});
Route::post('signup', [UsersController::class, 'signUp']);
Route::post('change_password',[UsersController::class,'updatePW']);
Route::post('forgot_password',[UsersController::class,'forgotPw']);
Route::post('reset_password', [UsersController::class, 'resetPw']);
Route::get('categories', [CategoryController::class, 'getCategories']);
/* Study route*/
Route::get('study', [StudysController::class, 'study']);
/* Terms route*/
Route::get('term', [TermsController::class, 'term']);
/* Dashboard route*/
Route::get('dashboard', [ExamController::class, 'dashboard_exam']);