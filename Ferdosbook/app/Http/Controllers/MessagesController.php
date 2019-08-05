<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Profile;
use App\notifications;
use App\Messages;
use Auth;

class MessagesController extends Controller
{
    public function index()
    {
      $users = User::where('id','!=',Auth::user()->id)->get();
      return view('messages.index')->with('users',$users);
    }
}
