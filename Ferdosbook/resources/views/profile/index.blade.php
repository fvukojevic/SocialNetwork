@extends('layouts.app')

@section('content')
    <div class="container">

        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{url('/home')}}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{url('profile')}}/{{Auth::user()->slug}}">Profile</a></li>
        </ol>

        <div class="row justify-content-center">
            @include('inc.sidebar')
            @foreach($userData as $uData)
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header">{{$uData->name}}</div>

                        <div class="card-body">
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-sm-6 col-md-4">
                                    <div class="thumbnail">
                                        <img src="{{url('/')}}/public/img/{{$uData->picture}}" width="80px" height="80px" class="rounded-circle"alt="">
                                        <br>
                                        @if(Auth::user()->id == $uData->user_id)
                                            <a href="{{route('changePhoto')}}">Change photo</a>
                                        @endif
                                        <div class="caption">
                                            <h3>{{ucwords($uData->name)}}</h3>
                                            <p>{{$uData->city}} - {{$uData->country}}</p>
                                            @if(Auth::user()->id == $uData->user_id)
                                                <p><a href="{{url('editProfile')}}" class="btn btn-primary" role="button">Edit Profile</a> <a href="#" class="btn btn-default" role="button"></a></p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4">
                                    <h4><span class="badge btn-primary">About</span></h4>
                                    <p>{{$uData->about}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card" style="margin-top:2%;">
                        <div class="card-header">
                            @if(Auth::user()->id == $uData->user_id) My Feed @else {{ $uData->name }} Feed @endif
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12 col-md-12">
                                    @foreach($posts as $post)
                                        <div class="row" style="border-bottom:1px solid #ccc; margin-bottom:7px">
                                            <div class="col-md-2 pull-left">
                                                <img src="{{url('/')}}/public/img/{{$uData->picture}}"
                                                     width="80px" height="80px"/>
                                            </div>

                                            <div class="col-md-7 pull-left">
                                                <h5 style="margin:0px;"><a href="{{url('/profile')}}/{{$uData->slug}}">
                                                        {{ucwords($uData->name)}}</a></h5>
                                                <small><i class="fas fa-globe" style="color:#3490dc;"></i>
                                                    <a style="color:#212529" href="{{url('/posts/singlePost')}}/{{$post->ID}}">{{date('F j, Y', strtotime($post->CreatedAt))}} at {{date('H: i', strtotime($post->CreatedAt))}}</a>
                                                </small>

                                                <br><span>{{$post->content}}</span><br>
                                                @if(Auth::user()->id == $uData->user_id)
                                                    <form id="my_form.{{ $post->ID }}" action="{{url('/post/delete')}}/{{$post->ID}}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        @method('DELETE')
                                                        <a href="{{url('/post/edit')}}/{{$post->ID}}">Edit</a> |
                                                        <a href="javascript:{}" onclick="document.getElementById('my_form.{{ $post->ID }}').submit(); return false;">Delete</a>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
