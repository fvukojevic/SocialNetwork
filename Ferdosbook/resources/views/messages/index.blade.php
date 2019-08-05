@extends('layouts.app')

@section('content')
  <div class="container">
    <div id="app" class="">
      @{{msg}}
    </div>
    <div class="row">
      <div class="col-md-3" style="background-color:#fff;">
        <h3 align="center">@{{msg}}</h3>
        <hr>
        @foreach($users as $user)
        <ul>
            <li style="list-style:none;
              margin-top:10px; background-color:#F3F3F3" class="row">
              <div class="col-md-3 pull-left">
                <img src="{{url('/')}}/public/img/{{$user->picture}}"
                style="width:50px; border-radius:100%; margin:5px">
              </div>
              <div class="col-md-9 pull-left" style="margin-top:5px">
              <b> {{$user->name}}</b><br>
              <small>Here we display message</small>
              </div>
            </li>
        </ul>
        @endforeach
      </div>

      <div class="col-md-7" style="background-color:#fff;">
        <h3 align="center">Messages</h3>
        <hr>
      </div>

      <div class="col-md-2" style="background-color:#fff;">
        <h3 align="center">User information</h3>
        <hr>
      </div>
    </div>
  </div>

@endsection
