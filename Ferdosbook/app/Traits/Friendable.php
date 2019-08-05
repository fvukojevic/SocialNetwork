<?php

namespace App\Traits;
use App\Friendship;

trait Friendable
{
   public function test()
   {
     return 'hi';
   }

   public function addFriend($user_id)
   {
     $friendship = Friendship::create([
       'requester' => $this->id, //user who is connected
       'user_requester' => $user_id
     ]);
     if($friendship)
     {
       return $friendship;
     }
     return 'failed';
   }
}
