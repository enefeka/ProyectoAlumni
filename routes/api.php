<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('users', 'UsersController');

Route::post('register', 'UsersController@post_create');
Route::post('login', 'UsersController@post_login');
Route::post('changepass', 'UsersController@post_changepassword');
Route::post('updateuser', 'UsersController@post_update');
Route::post('deleteuser', 'UsersController@post_delete');
Route::post('recover', 'UsersController@post_recover');
Route::post('sendrequest' , 'UsersController@post_sendRequest');
Route::get('listusers', 'UsersController@get_allusers');

Route::post('creategroup', 'GroupsController@post_create');
Route::post('deletegroup', 'GroupsController@post_delete');
Route::get('listgroups', 'GroupsController@get_groups');

Route::post('createevent', 'EventsController@post_create');
Route::post('updateevent', 'EventsController@post_update');
Route::post('deleteevent', 'EventsController@post_delete');
Route::get('listevents', 'EventsController@get_events');
Route::get('eventdata', 'EventsController@get_event');
Route::get('searchevent', 'EventsController@get_find');

Route::post('createcomment', 'CommentsController@post_create');
Route::post('deletecomment', 'CommentsController@post_delete');
