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
Route::post('responserequest', 'UsersController@post_responseRequest');
Route::post('deletefriend', 'UsersController@post_deleteFriend');
Route::post('cancelrequest', 'UsersController@post_cancelRequest');
Route::get('listusers', 'UsersController@get_allusers');
Route::get('validatemail', 'UsersController@get_validateMail');
Route::get('listfriends', 'UsersController@get_friends');
Route::get('finduser', 'UsersController@get_user');
Route::get('userbyid', 'UsersController@get_userById');

Route::post('creategroup', 'GroupsController@post_create');
Route::post('deletegroup', 'GroupsController@post_delete');
Route::post('assign', 'GroupsController@post_assign');
Route::post('unassign', 'GroupsController@post_unassign');
Route::get('listgroups', 'GroupsController@get_groups');
Route::get('groupsuser', 'GroupsController@get_groupsByUser');
Route::get('groupsuserclient', 'GroupsController@get_groupsByUserCliente');

Route::post('createevent', 'EventsController@post_create');
Route::post('updateevent', 'EventsController@post_update');
Route::post('deleteevent', 'EventsController@post_delete');
Route::get('listevents', 'EventsController@get_events');
Route::get('eventdata', 'EventsController@get_event');
Route::get('searchevent', 'EventsController@get_find');
Route::get('listtypes', 'EventsController@get_types');
Route::get('listcomments', 'EventsController@get_comments');
Route::get('eventsandroid', 'EventsController@get_eventsAndroid');
Route::get('calendar', 'EventsController@curl_calendar');

Route::post('createcomment', 'CommentsController@post_create');
Route::post('deletecomment', 'CommentsController@post_delete');

Route::post('createchat', 'ChatsController@post_create');
Route::post('sendmessage', 'ChatsController@post_sendMessage');
Route::get('messages', 'ChatsController@get_messages');
Route::get('chats', 'ChatsController@get_chats');
Route::get('userstochat', 'ChatsController@get_UsersToChat');
