<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Friendship;
use App\notifications;
use Auth;

class FriendsController extends Controller
{
    public function findFriends()
    {
      $uid = Auth::user()->id;
      $allusers = DB::table('profiles')->
                  leftJoin('users', 'users.id', '=', 'profiles.user_id')->
                  where('users.id', "!=", $uid)->
                  paginate(5);

      return view('friends.findFriends')->with('allUsers', $allusers);
    }

    public function addFriend($id)
    {
      Auth::user()->addFriend($id);

      return back()->with('success','Request sent');
    }

    public function myRequests()
    {
        $uid = Auth::user()->id;
        $friendRequests = DB::table('friendships')
          ->Join('users','users.id','=','friendships.requester')
          ->where('friendships.user_requester','=',$uid)
          ->where('status','=', 0)
          ->get();

        return view('friends.requests')->with('friendRequests', $friendRequests);
    }

    public function accept($name, $id)
    {
      $checkRequest = Friendship::where('requester', $id)
          ->where('user_requester', Auth::user()->id)
          ->first();

      if($checkRequest)
      {
        $update = DB::table('friendships')
           ->where('user_requester', Auth::user()->id)
           ->where('requester', $id)
           ->update(['status' => 1]);

        $notification = new notifications;
        $notification->user_sender_id = $id;
        $notification->user_accepter_id = Auth::user()->id;
        $notification->note = "accepted your friend request";
        $notification->status = '1';
        $notification->save();

        if($update)
        {
          return back()->with('success', "You are now friends with " . $name);
        }
      }
    }

    public function myFriends()
    {
      $friends1 = DB::table('friendships')
                  ->Join('users','users.id','=','friendships.user_requester') //not logged in but sent request
                  ->where('status','=',1)
                  ->where('requester','=',Auth::user()->id) //who is logged in
                  ->get();

      $friends2 = DB::table('friendships')
                  ->Join('users','users.id','=', 'friendships.requester') //not logged in but accepted
                  ->where('status','=',1)
                  ->where('user_requester','=', Auth::user()->id)//who is logged in
                  ->get();//who i sent a request

      $friends = array_merge($friends1->toArray(),$friends2->toArray());

      return view('friends.friends')->with('friends',$friends);
    }

    public function removeRequest($id)
    {
      DB::table('friendships')
          ->where('user_requester','=', Auth::user()->id)
          ->where('requester','=',$id)
          ->delete();//who i sent a request

      return back()->with('error','Friendship declined');
    }
}
