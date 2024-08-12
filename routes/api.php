<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['middleware' => ['cors', 'json.response']], function () {

    Route::post('login', [App\Http\Controllers\Api\ApiAuthController::class, 'login'])->name('login.api');

});

Route::group(['middleware' => ['auth:api', 'cors', 'json.response',]], function () {
    Route::get('/user', [App\Http\Controllers\Api\ApiAuthController::class, 'userInfo'])->name('userinfo.api');
    Route::post('/updateProfile', [App\Http\Controllers\Api\UserApiController::class, 'updateProfile'])->name('updateProfile.api');
    Route::post('/logout', [App\Http\Controllers\Api\ApiAuthController::class, 'logout'])->name('logout.api');
});

Route::group(['middleware' => [ 'cors', 'json.response', 'auth:api', 'roles:admin|manager']], function () {

    Route::get('/getUserRole', [App\Http\Controllers\Api\UserApiController::class, 'getUserRole'])->name('getUserRole.api');
    Route::get('/getAllProject', [App\Http\Controllers\Api\ProjectApiController::class, 'getAllProject'])->name('getAllProject.api');


    Route::get('/getTasks', [App\Http\Controllers\Api\TaskApiController::class, 'getTasks'])->name('getTasks.api');
    Route::post('/AddEditTask', [App\Http\Controllers\Api\TaskApiController::class, 'AddEditTask'])->name('AddEditTask.api');
    Route::get('/getTaskById/{id}', [App\Http\Controllers\Api\TaskApiController::class, 'getTaskById'])->name('getTaskById.api');
    Route::post('/AddComment/', [App\Http\Controllers\Api\TaskCommentsApiController::class, 'AddComment'])->name('AddComment.api');
    Route::get('/getComments/{id}', [App\Http\Controllers\Api\TaskCommentsApiController::class, 'getComments'])->name('getComments.api');

});


Route::group(['middleware' => [ 'cors', 'json.response', 'auth:api', 'admin']], function () {
    Route::get('/getUsers', [App\Http\Controllers\Api\Admin\AdminApiController::class, 'getUsers'])->name('getUsers.api');
    Route::post('/AddEditUser', [App\Http\Controllers\Api\Admin\AdminApiController::class, 'AddEditUser'])->name('AddEditUser.api');
    Route::get('/getUserById/{id}', [App\Http\Controllers\Api\Admin\AdminApiController::class, 'getUserById'])->name('getUserById.api');
});

Route::group(['middleware' => [ 'cors', 'json.response', 'auth:api', 'manager']], function () {
    Route::get('/getProjects', [App\Http\Controllers\Api\ProjectApiController::class, 'getProjects'])->name('getProjects.api');
    Route::post('/AddEditProject', [App\Http\Controllers\Api\ProjectApiController::class, 'AddEditProject'])->name('AddEditProject.api');
    Route::get('/getProjectById/{id}', [App\Http\Controllers\Api\ProjectApiController::class, 'getProjectById'])->name('getProjectById.api');
});