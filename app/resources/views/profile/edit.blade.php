@extends('layouts.app')

@section('content')
<div class="container">

  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{url('/home')}}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{url('profile')}}/{{Auth::user()->slug}}">Profile</a></li>
    <li class="breadcrumb-item"><a href="{{url('editProfile')}}">Edit Profile</a></li>
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

                    <div class="row">
                      <div class="col-sm-12 col-md-12">
                        <div class="thumbnail" style="padding-left:2%;">
                          <img src="{{url('/')}}/img/{{Auth::user()->picture}}" width="80px" height="80px" class="rounded-circle"alt="">
                          <div class="caption">
                            <h3>{{ucwords(Auth::user()->name)}}</h3>
                            <p>{{Auth::user()->profile->city}} - {{Auth::user()->profile->country}}</p>
                            <p><a href="{{route('changePhoto')}}" class="btn btn-primary" role="button">Change Photo</a> <a href="#" class="btn btn-default" role="button"></a></p>
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-12 col-md-12">


                       <form action="{{url('/updateProfile')}}" method="post">
                           <input type="hidden" name="_token" value="{{csrf_token()}}"/>

                           <div class="col-md-12">

                               <div class="input-group">
                                   <span class="input-group-text" id="basic-addon1">City name</span>
                                   <input type="text" class="form-control" placeholder="City Name" name="city" value="{{Auth::user()->profile->city}}">
                               </div>
                               <div class="input-group">
                                   <span class="input-group-text" id="basic-addon1">Country name</span>
                                   <input type="text" class="form-control" placeholder="Country Name" name="country" value="{{Auth::user()->profile->country}}">
                               </div>


                           </div>
                           <div class="col-md-12">
                               <div class="input-group">
                                   <span class="input-group-text" id="basic-addon1">About</span>
                                   <textarea type="text" class="form-control" name="about">{{Auth::user()->profile->about}}</textarea>
                               </div>

                               <br>

                               <div class="input-group">
                                   <input type="submit" class="btn btn-success pull-right" >
                               </div>
                           </div>
                       </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
