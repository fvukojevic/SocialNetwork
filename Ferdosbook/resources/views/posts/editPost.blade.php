@extends('layouts.app')

@section('content')

    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{url('/home')}}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{url('/post/edit')}}/{{$post->ID}}">Edit Post</a></li>
        </ol>
        <div class="row justify-content-center">
            @include('inc.sidebar')
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">Edit Post</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <div class="row">
                                    <div class="col-md-2 pull-left">
                                        <img src="{{url('/')}}/public/img/{{Auth::user()->picture}}"
                                             width="80px" height="80px"/>
                                    </div>
                                    <div class="col-md-10 pull-right">
                                        <form action="{{url('/post/update')}}/{{$post->ID}}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <textarea id="postText" style="width:100%!important"class="form-control"name="content" rows="2" cols="80"
                                                       name="content">{{ $post->content }}</textarea>
                                            <input type="submit" value="Post"class="btn btn-sm btn-primary pull-right"
                                                   style="position:relative; float:right; margin:10px; padding:5 15 5 15; background-color:##3490dc!important" id="postBtn">
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
