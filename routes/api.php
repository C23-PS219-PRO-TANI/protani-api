<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ExampleController;
use App\Http\Controllers\LogActivityController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UserLocationController;
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

// article
Route::get('article/all', function () {
    return response()->json(['message' => 'hai, hallo from backend'], 200);
})->name('view_all_articles');
Route::get('article/view/{id}', function () {
    return response()->json(['message' => 'hai, hallo from backend'], 200);
})->name('view_article');
Route::post('article/create', function () {
    return response()->json(['message' => 'hai, hallo from backend'], 201);
})->name('create_article');
Route::post('article/update/content/{id}', function () {
    return response()->json(['message' => 'hai, hallo from backend'], 204);
})->name('update_article_content');
Route::post('article/update/header/{id}', function () {
    return response()->json(['message' => 'hai, hallo from backend'], 204);
})->name('update_article_header');
Route::post('article/delete/{id}', function () {
    return response()->json(['message' => 'hai, hallo from backend'], 204);
})->name('delete_article');


// Admin
Route::middleware([])->group(function () {
    Route::get('admin', [AdminController::class, 'index'])->name('admin_index');
    Route::post('admin/create-admin', [AdminController::class, 'index'])->name('admin_create');
    Route::post('admin/banned-user', [AdminController::class, 'index'])->name('admin_bannedUser');
    Route::get('admin/get-all-users', [AdminController::class, 'index'])->name('admin_getAllUsers');
    Route::get('admin/get-user/{id}', [AdminController::class, 'index'])->name('admin_getUser');
});

// example end point
Route::any('example/AnySuccess', [ExampleController::class, 'AnySuccess']);
Route::get('example/Success', [ExampleController::class, 'Success']);
Route::get('example/ErrNotFound', [ExampleController::class, 'ErrNotFound']);
Route::get('example/ErrBadRequest', [ExampleController::class, 'ErrBadRequest']);
Route::get('example/ErrPayloadNotValid', [ExampleController::class, 'ErrPayloadNotValid']);
Route::get('example/ErrIDNotValid', [ExampleController::class, 'ErrIDNotValid']);
Route::get('example/ErrInternalServer', [ExampleController::class, 'ErrInternalServer']);
Route::get('example/ErrDuplicateData', [ExampleController::class, 'ErrDuplicateData']);

// test endpoint
Route::get('test/pingDatabase', [TestController::class, 'pingDatabase']);
Route::get('test/create', [TestController::class, 'createTest']);
Route::get('test/update', [TestController::class, 'updateTest']);
Route::get('test/delete', [TestController::class, 'deleteTest']);

Route::post('user-loc/create', [UserLocationController::class, 'create']);
Route::get('user-loc/get/{id}', [UserLocationController::class, 'get']);
Route::get('user-loc/all', [UserLocationController::class, 'getAll']);

Route::post('log-activity/create', [LogActivityController::class, 'create']);
Route::get('log-activity/get/{id}', [LogActivityController::class, 'get']);
Route::get('log-activity/all', [LogActivityController::class, 'getAll']);
