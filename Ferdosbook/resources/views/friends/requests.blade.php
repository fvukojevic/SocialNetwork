@extends('layouts.app')

@section('content')
<div class="container">

  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{url('/home')}}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{url('profile')}}/{{Auth::user()->slug}}">Profile</a></li>
    <li class="breadcrumb-item"><a href="{{url('friends')}}">Friends</a></li>
    <li class="breadcrumb-item"><a href="">Requests</a></li>
  </ol>

    <div class="row justify-content-center">
        @include('inc.sidebar')
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">Friend Requests</div>

                <div class="card-body">
                  @include('inc.messages')
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="row">
                      <div class="col-sm-12 col-md-12">
                          @foreach($friendRequests as $users)
                          <div class="row" style="border-bottom:1px solid #ccc; margin-bottom:15px">
                            <div class="col-md-2 pull-left">
                                <img src="{{url('/')}}/public/img/{{$users->picture}}"
                                width="80px" height="80px" class="rounded-circle"/>
                            </div>

                            <div class="col-md-7 pull-left">
                                <h3 style="margin:0px;"><a href="{{url('/profile')}}/{{$users->slug}}">
                                  {{ucwords($users->name)}}</a></h3>
                                <p><b>Gender: {{$users->gender}}<b></p>
                                <p><b>Email: {{$users->email}}<b></p>

                            </div>

                            <div class="col-md-3 pull-right">
                                  <a href="{{url('/friends/accept')}}/{{$users->name}}/{{$users->id}}"
                                     class="btn btn-info" style="color:#fff" >Accept</a>
                                     <br>
                                     <br>
                                     <a href="{{url('/friends/remove')}}/{{$users->id}}"
                                        class="btn btn btn-outline-secondary" >Decline</a>
                            </div>
                            </div>
                          @endforeach
                      </div>
                    </div>
                </div>
        </div>
    </div>
</div>
@endsection
