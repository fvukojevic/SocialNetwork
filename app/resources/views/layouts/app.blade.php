<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>

    <title>{{ config('app.name', 'FerdoBook') }}</title>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>

<div id="app">
    <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/home') }}">
                {{ config('FerdoBook', 'FerdoBook') }}
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">
                    <!-- Authentication Links -->
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        <li class="nav-item">
                            @if (Route::has('register'))
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            @endif
                        </li>
                    @else
                        <li>
                            <a class="nav-link" href="{{url('/friends')}}"><i class="fas fa-users fa-2x"></i></a>
                        </li>
                        <li>
                            <a class="nav-link" href="{{url('/messages')}}"><i class="fas fa-comment-alt fa-2x"></i></a>
                        </li>
                        <li class="nav-item dropdown">
                            <a href="#" class="nav-link" data-toggle="dropdown"><i class="fas fa-globe-americas fa-2x"></i>
                                <span class="badge" style="background:red; position:relative; top:-15px; color:#fff; left:-10px;">
                                  {{App\notifications::where('status','=',1)
                                    ->where('user_sender_id',Auth::user()->id)
                                    ->count()
                                    }}
                                </span></a>
                            <?php
                            $notes = DB::table('users')
                                ->leftJoin('notifications','users.id','notifications.user_accepter_id')
                                ->where('user_sender_id', '=', Auth::user()->id)
                                //->where('status', '=', 1)
                                ->orderBy('notifications.created_at','DESC')
                                ->limit(5)
                                ->get();
                            ?>
                            <ul class="dropdown-menu" style="width:350px!important"role="menu">
                                @foreach($notes as $note)
                                    @if($note->status == 1)
                                        <li class="nav-item" style="background-color:#E4E9F2; margin-bottom:5px;">
                                    @else
                                        <li class="nav-item" style="margin-bottom:5px;">
                                            @endif
                                            <div class="row">
                                                <div class="col-md-2 pull-right">
                                                    <img src="{{url('/')}}/img/{{$note->picture}}" style="width:50px; height:50px; margin-left:5px; border-radius:5px;">
                                                </div>
                                                <div class="col-md-10">
                                                    <a href="{{url('notifications')}}/{{$note->id}}" class="nav-link">
                                                        <b style="color:green; font-size:90%;">{{ucwords($note->name)}}</b>
                                                        <span style="font-size:90%; color:#000;">
                                             {!! $note->note !!}
                                          </span>
                                                        <br>
                                                        <small><i class="fas fa-users" style="color:#3490dc;"></i>
                                                            {{date('F j, Y', strtotime($note->created_at))}} at {{date('H: i', strtotime($note->created_at))}}
                                                        </small>
                                                    </a>
                                                </div>
                                            </div>
                                        </li>
                                        @endforeach
                                        <hr>
                                        <li class="nav-item" style="font-size:12px; float:right; margin-right:10px;">
                                            <a href="{{url('notifications')}}">See all notifications</a>
                                        </li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                <img src="{{url('/')}}/img/{{Auth::user()->picture}}" width="40px" height="40px" alt="" class="rounded-circle"> {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ url('/profile') }}/{{Auth::user()->slug}}">Profile</a>

                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        @yield('content')
    </main>
</div>
<script src="{{ asset('js/profile.js') }}" defer></script>
</body>
</html>