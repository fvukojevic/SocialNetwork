@extends('layouts.app')

@section('content')
<div class="container">

  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{url('/home')}}">Home</a></li>
    <li class="breadcrumb-item"><a href="">Notifications</a></li>
  </ol>

    <div class="row justify-content-center">
        @include('inc.sidebar')
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">Notifications</div>

                <div class="card-body">
                    <div class="row">
                      <div class="col-sm-12 col-md-12">
                          @foreach($notes as $note)
                            <a href="{{url('profile')}}/{{$note->slug}}"><b style="color:green">{{$note->name}}</b></a> {{$note->note}}
                            @if($note->note == "accepted your friend request")
                            <br>
                            <small><i class="fas fa-users" style="color:#3490dc;"></i>
                              {{date('F j, Y', strtotime($note->created_at))}} at {{date('H: i', strtotime($note->created_at))}}
                            </small>
                            @endif
                            <hr>
                          @endforeach
                      </div>
                    </div>
                </div>
        </div>
    </div>
</div>
@endsection
