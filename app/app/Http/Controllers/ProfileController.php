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

    public function sendMessage(Request $request)
    {
        $conId = $request->conID;
        $msg = $request->msg;

        $fetch_userTo = DB::table('messages')->where('conversation_id', $conId)
        ->where('user_to', '!=', Auth::user()->id)
        ->get();

        $userTo = $fetch_userTo[0]->user_to;

        $sendMessage = DB::table('messages')->insert([
           'user_to' => $userTo,
           'user_from' => Auth::user()->id,
           'msg' => $msg,
           'status' => 1,
           'conversation_id' => $conId
        ]);

        if($sendMessage) {
            $userMsg = DB::table('messages')
                ->join('users', 'users.id', 'messages.user_from')
                ->where('messages.conversation_id', $conId)->get();
            return $userMsg;
        }
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
