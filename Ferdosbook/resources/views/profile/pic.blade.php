@extends('layouts.app')

@section('content')
<div class="container">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{url('/home')}}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{url('profile')}}/{{Auth::user()->slug}}">Profile</a></li>
    <li class="breadcrumb-item"><a href="{{url('editProfile')}}">Edit Profile</a></li>
    <li class="breadcrumb-item"><a href="{{url('changePhoto')}}">Change Photo</a></li>
  </ol>
    <div class="row justify-content-center">
        @include('inc.sidebar')
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">{{Auth::user()->name}}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    Welcome to your profile
                    <br>
                    <img src="{{url('/')}}/public/img/{{Auth::user()->picture}}" width="100px" height="100px" alt="">
                    <br>
                    <hr>
                    <form class="" action="{{url('uploadPhoto')}}" method="post" enctype="multipart/form-data">
                      <input type="hidden" name="_token" value="{{csrf_token()}}">
                      <input type="file" name="picture" class="form-control">
                      <input type="submit" name="submit" class="btn btn-success">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
