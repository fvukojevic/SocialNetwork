<div class="col-md-3">
  <div class="card">
    <div class="card-header">
      <h5 class="center">Sidebar</h5>
      @auth
      <hr>
      <h6><a class="nav-link" href="{{ url('/friends/find')}}"><i class="fas fa-lg fa-search-plus"></i> Find Friends</a></h6>
      <h6><a class="nav-link" href="{{url('/friends')}}"><i class="fas fa-lg fa-user-friends"></i> Friends</a></h6>
      <h6><a a class="nav-link" href="{{ url('/friends/requests')}}"><i class="far fa-lg fa-handshake"></i> My requests<b>(
        {{App\Friendship::where('status','=',0)->where('user_requester','=',Auth::user()->id)->count()}}
        )</b></a></h6>
      @endauth
    </div>
  </div>
</div>
