@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @include('inc.sidebar')
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">Create New Status</div>

                    <div class="card-body">
                        @include('inc.messages')
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <div class="row">
                                    <div class="col-md-2 pull-left">
                                        <img src="{{url('/')}}/img/{{Auth::user()->picture}}"
                                             width="80px" height="80px"/>
                                    </div>
                                    <div class="col-md-10 pull-right">
                                        <form action="{{ route('postStatus') }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <textarea id="postText" style="width:100%!important"class="form-control"name="content" rows="2" cols="80"
                                                      placeholder="What's on your mind?" name="content"></textarea>
                                            <input type="submit" value="Post"class="btn btn-sm btn-primary pull-right"
                                                   style="position:relative; float:right; margin:10px; padding:5 15 5 15; background-color:##3490dc!important" id="postBtn">
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card" style="margin-top:2%;">
                    <div class="card-header">
                        Newsfeed
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                @foreach($posts as $post)
                                    <div class="row" style="border-bottom:1px solid #ccc; margin-bottom:7px">
                                        <div class="col-md-2 pull-left">
                                            <img src="{{url('/')}}/img/{{$post->picture}}"
                                                 width="80px" height="80px"/>
                                        </div>

                                        <div class="col-md-7 pull-left">
                                            <h5 style="margin:0px;"><a href="{{url('/profile')}}/{{$post->slug}}">
                                                    {{ucwords($post->name)}}</a></h5>
                                            <small><i class="fas fa-globe" style="color:#3490dc;"></i>
                                                <a style="color:#212529" href="{{url('/posts/singlePost')}}/{{$post->ID}}">{{date('F j, Y', strtotime($post->CreatedAt))}} at {{date('H: i', strtotime($post->CreatedAt))}}</a>
                                            </small>

                                            <br><p>{{$post->content}}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
