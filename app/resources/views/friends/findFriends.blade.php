@extends('layouts.app')

@section('content')
<div class="container">

  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{url('/home')}}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{url('profile')}}/{{Auth::user()->slug}}">Profile</a></li>
    <li class="breadcrumb-item"><a href="{{url('friends')}}">Friends</a></li>
    <li class="breadcrumb-item"><a href="">Find Friends</a></li>
  </ol>

    <div class="row justify-content-center">
        @include('inc.sidebar')
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">Find and connect with your Friends</div>

                <div class="card-body">
                  @include('inc.messages')
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="row">
                      <div class="col-sm-12 col-md-12">
                          @foreach($allUsers as $users)
                          <div class="row" style="border-bottom:1px solid #ccc; margin-bottom:15px">
                            <div class="col-md-2 pull-left">
                                <img src="{{url('/')}}/img/{{$users->picture}}"
                                width="80px" height="80px" class="rounded-circle"/>
                            </div>

                            <div class="col-md-7 pull-left">
                                <h3 style="margin:0px;"><a href="{{url('/profile')}}/{{$users->slug}}">
                                  {{ucwords($users->name)}}</a></h3>
                                <p><i class="fa fa-globe"></i> {{$users->city}}  - {{$users->country}}</p>
                                <p>{{$users->about}}</p>

                            </div>

                            <div class="col-md-3 pull-right">

                                <?php
                                $check = DB::table('friendships')
                                        ->where('user_requester', '=', $users->id)
                                        ->where('requester', '=', Auth::user()->id)
                                        ->first();
                                $status2 = DB::table('friendships')
                                          ->where('user_requester', '=', Auth::user()->id)
                                          ->where('requester', '=', $users->id)
                                          ->where('status','=',1)
                                          ->first();
                                if($check !='' || $status2!=''){
                                  $status = DB::table('friendships')
                                          ->where('user_requester', '=', $users->id)
                                          ->where('requester', '=', Auth::user()->id)
                                          ->where('status','=',1)
                                          ->first();
                                        if($status =='' && $status2 ==''){
                                ?>
                                   <p>
                                        Request already sent
                                    </p>
                                  <?php } else{?>
                                    <p>Friends</p>
                                  <?php }?>
                                <?php } else {?>
                                  <a href="{{url('/')}}/addFriend/{{$users->id}}"
                                     class="btn btn-info" style="color:#fff;" >Add a Friend</a>
                                <?php }?>
                            </div>
                            </div>
                          @endforeach
                          <?php echo $allUsers->render(); ?>
                      </div>
                    </div>
                </div>
        </div>
    </div>
</div>
@endsection
