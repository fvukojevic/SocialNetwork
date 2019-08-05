<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;
use Auth;

class ProfileController extends Controller
{
    /**
     * Returns users profile and his/hers posts
     *
     * @param $slug
     * @return mixed
     */
    public function index($slug)
    {
      $userData = User::leftJoin('profiles','profiles.user_id','users.id')->where('slug','=',$slug)->get();

      $client = $this->getClient();
      $res = $client->post('http://localhost:8888/post/getMyPosts/' . $userData[0]->user_id);

      $posts = json_decode($res->getBody()->getContents());
      $postsCollection = collect($posts);

      return view('profile.index')
          ->with('userData', $userData)
          ->with('data', Auth::user()->profile)
          ->with('posts', $postsCollection);
    }

    public function changePhoto()
    {
      return view('profile.pic');
    }

    public function uploadPhoto(Request $request)
    {
      $user = User::find(Auth::user()->id);

      $file = $request->file('picture');
      $filename = $file->getClientOriginalName();

      $path = 'public/img';
      $file->move($path,$filename);

      $user->picture = $filename;
      $user->save();
      return back();
    }

    public function editProfile()
    {
      return view('profile.edit');
    }

    public function updateProfile(Request $request)
    {
      DB::table('profiles')->where('user_id', Auth::user()->id)->update($request->except('_token'));
      return back();
    }

    public function notifications($id)
    {
      $notes = DB::table('notifications')
                ->leftJoin('users','users.id','notifications.user_accepter_id')
                ->where('notifications.id', $id)
                ->where('user_sender_id', '=', Auth::user()->id)
                ->orderBy('notifications.created_at','DESC')
                ->get();

    $updateNotifications = DB::table('notifications')
                        ->where('user_sender_id', Auth::user()->id)
                        ->where('notifications.id', $id)
                        ->update(['status' => 0]);

      return view('profile.notes')->with('notes', $notes);
    }

    public function allNotifications()
    {
      $notes = DB::table('notifications')
                ->leftJoin('users','users.id','notifications.user_accepter_id')
                ->where('user_sender_id', '=', Auth::user()->id)
                //->where('status','=',1)
                ->orderBy('notifications.created_at','DESC')
                ->get();

    $updateNotifications = DB::table('notifications')
                        ->where('user_sender_id', Auth::user()->id)
                        ->update(['status' => 0]);

      return view('profile.notes')->with('notes', $notes);
    }

    /**
     * Returns a GuzzleClient we use for go routing
     *
     * @return Client
     */
    private function getClient(){
        return new Client([
            'headers' => ['Content-Type' => 'application/json']
        ]);
    }
}
