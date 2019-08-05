@extends('layouts.app')

@section('content')
<div class="container">

  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{url('/home')}}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{url('profile')}}/{{Auth::user()->slug}}">Profile</a></li>
    <li class="breadcrumb-item"><a href="">Friends</a></li>
  </ol>

    <div class="row justify-content-center">
        @include('inc.sidebar')
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">My friends</div>

                <div class="card-body">
                  @include('inc.messages')
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="row">
                      <div class="col-sm-12 col-md-12">
                          @foreach($friends as $friend)
                          <div class="row" style="border-bottom:1px solid #ccc; margin-bottom:15px">
                            <div class="col-md-2 pull-left">
                                <img src="{{url('/')}}/public/img/{{$friend->picture}}"
                                width="80px" height="80px" class="rounded-circle"/>
                            </div>

                            <div class="col-md-7 pull-left">
                                <h3 style="margin:0px;"><a href="{{url('/profile')}}/{{$friend->slug}}">
                                  {{ucwords($friend->name)}}</a></h3>
                                <p><b>Gender: {{$friend->gender}}<b></p>
                                <p><b>Email: {{$friend->email}}<b></p>

                            </div>

                            <div class="col-md-3 pull-right">
                                <a href="{{url('unfriend')}}/{{$friend->id}}"
                                  class="btn btn-outline-secondary" >UnFriend</a>
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
