<?php

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

use App\User;

Route::get('/', function(){
  return view('welcome');
});
Auth::routes();


//Auth functionalities
Route::group(['middleware' => 'auth'], function(){
  Route::get('/home', 'HomeController@index')->name('home');
  Route::get('/profile/{slug}','ProfileController@index')->name('profile');
  Route::get('/changePhoto', 'ProfileController@changePhoto')->name('changePhoto');
  Route::post('/uploadPhoto', 'ProfileController@uploadPhoto')->name('uploadPhoto');
  Route::get('/editProfile','ProfileController@editProfile')->name('editProfile');
  Route::post('updateProfile','ProfileController@updateProfile')->name('updateProfile');

  //for Friends
  Route::get('/friends/find', 'FriendsController@findFriends')->name('findFriends');
  Route::get('/friends','FriendsController@myFriends')->name('myFriends');
  Route::get('/addFriend/{id}','FriendsController@addFriend')->name('addFriend');
  Route::get('/friends/requests', 'FriendsController@myRequests')->name('myrequests');
  Route::get('/friends/remove/{id}', 'FriendsController@removeRequest');
  Route::get('/friends/accept/{name}/{id}', 'FriendsController@accept')->name('accept');
  Route::get('/unfriend/{id}', function($id){
              $loggedUser = Auth::user()->id;
             DB::table('friendships')
             ->where('requester', $loggedUser)
             ->where('user_requester', $id)
             ->delete();
             DB::table('friendships')
             ->where('user_requester', $loggedUser)
             ->where('requester', $id)
             ->delete();
              return back()->with('error', 'You are no longer friends with this person');
  });

  //for notifications
  Route::get('/notifications/{id}','ProfileController@notifications')->name('notify');
  Route::get('/notifications','ProfileController@allNotifications')->name('all_notes');

  //for posts
  Route::get('/home','PostsController@index');
  Route::post('/posts/addPost', 'PostsController@store')->name('postStatus');
  Route::get('/posts/singlePost/{id}','PostsController@singlePost')->name('singlePost');
  Route::get('/post/edit/{id}', 'PostsController@edit')->name('edit');
  Route::post('/post/update/{id}', 'PostsController@update')->name('update');
  Route::delete('/post/delete/{id}', 'PostsController@delete')->name('delete');

  //for comments
  Route::post('/comments/addComment', 'CommentsController@store')->name('postComment');
  Route::delete('/comment/delete/{if}', 'CommentsController@delete')->name('deleteComment');



    Route::get('/messages', function(){
        return view('messages');
    });
    // messenger start
    Route::get('/getMessages', function(){

        $allUsers1 = DB::table('users')
            ->Join('conversations','users.id','conversations.user_one')
            ->where('conversations.user_two', Auth::user()->id)->get();

        $allUsers2 = DB::table('users')
            ->Join('conversations','users.id','conversations.user_two')
            ->where('conversations.user_one', Auth::user()->id)->get();
        return array_merge($allUsers1->toArray(), $allUsers2->toArray());
    });

    Route::get('/getMessages/{id}', function($id){
        $userMsg = DB::table('messages')
            ->join('users', 'users.id', 'messages.user_from')
            ->where('messages.conversation_id', $id)->get();
        return $userMsg;
    });

    Route::post('/sendMessage', 'ProfileController@sendMessage');
});
