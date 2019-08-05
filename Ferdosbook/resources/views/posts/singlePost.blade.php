@extends('layouts.app')

@section('content')

    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{url('/home')}}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{url('/posts/singlePost')}}/{{$post->ID}}">Single Post</a></li>
        </ol>
        <div class="row justify-content-center">
            @include('inc.sidebar')
            <div class="col-md-9">
                <div class="card">
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
                                        <img src="{{url('/')}}/public/img/{{$post->picture}}"
                                             width="80px" height="80px"/>
                                    </div>
                                    <div class="col-md-10 pull-right">
                                        <h5 style="margin:0px;"><a href="{{url('/profile')}}/{{$post->slug}}">
                                                {{ucwords($post->name)}}</a></h5>
                                        <small><i class="fas fa-globe" style="color:#3490dc;"></i>
                                            <a style="color:#212529"
                                               href="{{url('/posts/singlePost')}}/{{$post->ID}}">{{date('F j, Y', strtotime($post->CreatedAt))}}
                                                at {{date('H: i', strtotime($post->CreatedAt))}}</a>
                                        </small>
                                        <p>{{$post->content}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">

                        <div class="row">
                            <div class="col-md-1">
                                <img src="{{url('/')}}/public/img/{{Auth::user()->picture}}"
                                     width="60px" height="60px"/>
                            </div>
                            <div class="col-md-11">

                                <form style="width:auto;" action="{{ route('postComment') }}" method="POST"
                                      enctype="multipart/form-data">
                                    @csrf
                                    <textarea id="postText" style="width:100%!important; resize:none;" class="form-control"
                                              name="content" rows="2" cols="80"
                                              placeholder="Comment here..." name="content"></textarea>
                                    <input type="hidden" name="post_id" value="{{ $post->ID }}"/>
                                    <input type="submit" value="Comment"
                                           class="btn btn-sm btn-primary"
                                           style="position:absolute;color:#3490dc;bottom:0px;right:16px;background-color:Transparent!important"
                                           id="postBtn">
                                </form>

                            </div>
                        </div>
                    </div>
                    @foreach($comments as $comment)
                        <div class="card-footer">

                            <div class="row">
                                <div class="col-md-1">
                                    <img src="{{url('/')}}/public/img/{{$comment->picture}}"
                                         width="60px" height="60px"/>
                                </div>
                                <div class="col-md-10 offset-1">
                                    <h5 style="margin:0px;"><a href="{{url('/profile')}}/{{$comment->slug}}">
                                            {{ucwords($comment->name)}}</a></h5>
                                    <small><i class="fas fa-globe" style="color:#3490dc;"></i>
                                        {{date('F j, Y', strtotime($comment->CreatedAt))}}
                                        at {{date('H: i', strtotime($comment->CreatedAt))}}
                                    </small>
                                    <p>{{$comment->content}}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
@endsection
