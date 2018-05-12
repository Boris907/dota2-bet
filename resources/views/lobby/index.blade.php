@extends('layouts.app')

@section('content')
    <div class="container">
        @if($errors->any())
            <h4>{{$errors->first()}}</h4>
        @endif
        <div class="row">
            <div class="col-md-10 col-md-offset-1 text-center">
                <h3>You are in lobby</h3>
                <h3>Your cash:<span id="max">{{auth()->user()->coins}}</span>$</h3>
                <h3 id="bank">Current bank in this room:{{$bank}}$</h3>
                <button id="exit" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal2">Exit
                </button>
                <a href="/rooms/lobby/{{$game_id}}/all" style="margin:5px" class="btn btn-primary">Fill lobby
                </a>
                <br>
                <br>
                <button id="change" class="btn btn-primary text-center" data-toggle="modal" style="visibility: hidden;"  data-target="#exampleModal">Increase
                    your bet
                </button>
            </div>
        </div>
    </div>
    <br>
    <br>
    <br>
    <br>

    <div class="container">
        <div class="card col-md-6">
            <div class="card-header">
                Radiant
            </div>
            <ul class="list-group list-group-flush">
                @foreach($radiant as $place_id => $playerID)
                    @if($playerID['uid'] == 0)
                        <li class="list-group-item round">
                            <a class="take_place" href="/rooms/lobby/{{$game_id}}/place/{{$place_id}}">Take place</a>
                        </li>
                    @else
                        <li class="list-group-item round">
                            @if(auth()->user()->player_id == $playerID['uid'])
                                <span href="/rooms/lobby/{{$game_id}}/place/{{$place_id}}/set" class="place" id="button_{{ $place_id }}"></span>
                            @endif
                            | <a href="/profile/{{ $playerID['uid'] }}">{{ $playerID['uid'] }}</a> |
                            <span id="bet_{{$playerID['uid']}}">{{ $playerID['bet'] }}</span>$
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
        <div class="card col-md-6">
            <div class="card-header">
                Dire
            </div>
            <ul class="list-group list-group-flush">
                @foreach($dire as $place_id => $playerID)
                    @if($playerID['uid'] == 0)
                        <li class="list-group-item round">
                            <a href="/rooms/lobby/{{$game_id}}/place/{{$place_id}}">Take place</a></li>
                    @else
                        <li class="list-group-item round">
                            @if(auth()->user()->player_id == $playerID['uid'])
                                <span href="/rooms/lobby/{{$game_id}}/place/{{$place_id}}/set" class="place" id="button_{{ $place_id }}"><i class="icon-ok"></i></span>
                            @endif
                            | <a href="/profile/{{$playerID['uid']}}">{{$playerID['uid']}}</a> |
                            <span id="bet_{{$playerID['uid']}}">{{$playerID['bet']}}</span>$
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>

        {{--<a href="/rooms/lobby/{{$game_id}}/start" class="btn btn-success not-active" id="start-game">Start game</a>--}}
    </div>
    <div class="go"></div>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-center" id="exampleModalLabel">Increase your bet</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="#" method="post">
                        <meta name="csrf-token" content="{{ csrf_token() }}">
                        <meta name="lobby_id" content="{{$game_id}}">
                        <meta name="user_id" content="{{auth()->user()->player_id}}">
                        <div class="input-group col-md-12">
                            <label style="padding:12px ">Increase bet for: </label>
                            <button type="button" style="margin:5px" class="btn btn-primary bet_submit" value="1">1$
                            </button>
                            <button type="button" style="margin:5px" class="btn btn-primary bet_submit" value="2">2$
                            </button>
                            <button type="button" style="margin:5px" class="btn btn-primary bet_submit" value="5">5$
                            </button>
                            <button type="button" style="margin:5px" class="btn btn-primary bet_submit" value="10">10$
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-center" id="exampleModalLabel2">If you leave, you will lose your minimal
                        bet</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="input-group col-md-12">
                        <label style="padding:12px ">Leave?</label>
                        <a href="/rooms/lobby/exit" style="margin:5px" class="btn btn-primary">Y
                        </a>
                        <button type="submit" style="margin:5px" class="btn btn-primary exit" value="no">N
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
       window.onload =  function () {
           $('.place').on('click', function () {
               var id = $('.place').attr('id');
               var ready = document.getElementById(id);
               ready.innerHTML = "Ready!";
               ready.setAttribute("color:", "green");
               ready.setAttribute("class", "non-click");


               var link = document.getElementById(id).getAttribute("href");
               var room_id = link.split('/')[3];
               var place = link.split('/')[5];

               $.ajax({
                    type: "GET",
                    url: room_id + '/place/' + place + '/set'
               });
           });

           var timerId = setTimeout(function tick() {
               $.get(window.location.pathname + '/get', function (response) {
                   console.log(response.length);
                   if (response.length >= 2) {
                       clearTimeout(timerId);
                       document.getElementById('change').setAttribute("style", "visibility: visible");
                       setTimeout(function () {
                           document.getElementById('change').setAttribute("style", "visibility: hidden");
                           setTimeout(function () {
                               window.location.href = window.location.pathname + '/start';
                           }, 10000);
                       }, 10000);
                   }
               });
               timerId = setTimeout(tick, 5000);
           }, 5000);
        };
    </script>
@endsection