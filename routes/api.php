<?php

use App\Http\Controllers\Notification\FriendRequestController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Post\PostController;
use App\Http\Controllers\Notification\ConversationController;
use App\Http\Controllers\Notification\MessageController;
use App\Http\Controllers\Notification\NotificationController;
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



Route::post('/user/register',[AuthController::class,"register"])->middleware('guest:sanctum');
Route::post('/user/login',[AuthController::class,"login"])->middleware('guest:sanctum');
Route::delete('/user/logout/{token?}',[AuthController::class,"logout"]);


Route::get('/email/verify/{id}',[\App\Http\Controllers\VerificationController::class,"verify"])->name('verification');
Route::get('/user/',[UserController::class,'index']);
Route::post('/user/addImageProfile',[UserController::class,'addImageProfile']);
Route::post('/user/addCoverProfile',[UserController::class,'addCoverProfile']);

Route::post('/user/addPost',[PostController::class,'addPost']);
Route::get('/post/getPosts',[PostController::class,'getPosts']);
Route::delete('/post/deletePost/{id}',[PostController::class,'deletePost']);

Route::post('/user/friend-request/{id}',[FriendRequestController::class,'sendFriendRequest']);


Route::middleware('auth:sanctum')->group(function () {
    // Conversations
    Route::get('/conversations', [ConversationController::class,'index']);
    Route::post('/conversations', [ConversationController::class,'store']);
    Route::get('/conversations/{conversation}', [ConversationController::class,'show']);
    Route::post('/conversations/{conversation}/users', [ConversationController::class,'addUser']);
    // Messages
    Route::get('/conversations/{conversation}/messages', [MessageController::class,'index']);
    Route::post('/conversations/{conversation}/messages', [MessageController::class,'store']);
});

Route::middleware('auth:sanctum')->group(function () {
    // ...

    // Notifications
    Route::get('/notifications', [NotificationController::class,'index']);
    Route::put('/notifications/{notification}', [NotificationController::class,'update']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/friend-requests', [UserController::class, 'sendFriendRequest']);
    Route::get('/friend-requests', [UserController::class, 'getFriendRequests']);
    Route::put('/friend-requests/accept', [UserController::class, 'acceptFriendRequest']);
    Route::put('/friend-requests/reject', [UserController::class, 'rejectFriendRequest']);
});
