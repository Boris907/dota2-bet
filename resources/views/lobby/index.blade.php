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
                <button id="change" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">Increase
                    your bet
                </button>
                <button id="exit" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal2">Exit
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
                                <a href="/rooms/lobby/{{$game_id}}/place/{{$place_id}}/set" class="btn btn-success place" id="button_{{ $place_id }}">Ready</a>
                            @endif
                            <a href="/profile/{{ $playerID['uid'] }}">{{ $playerID['uid'] }}</a> |
                            <span id="bet">{{ $playerID['bet'] }}</span>$
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
                                <a href="/rooms/lobby/{{$game_id}}/place/{{$place_id}}/set" class="btn btn-success place" id="button_{{ $place_id }}">Ready</a>
                            @endif
                            <a href="/profile/{{$playerID['uid']}}">{{$playerID['uid']}}</a> |
                            <span id="bet">{{$playerID['bet']}}</span>$
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
                        <a href="{{ url("/rooms/lobby/exit") }}" style="margin:5px" class="btn btn-primary">Y
                        </a>
                        <button type="submit" style="margin:5px" class="btn btn-primary exit" value="no">N
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        window.onload = function () {
            $('.place').on('click', function () {
                var id = $('.place').attr('id');
                var button = $('#' + id).prop("disabled", true);
            });
//                $.ajax({
//                    headers: {
//                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//                    },
//                    type: "POST",
//                    url: '/profile/setId',
//                    data: {
//                        id:id
//                    },
//                    success: function (response) {
//                        alert(response);
//                    }
//                });
//            });
//            $('input[type="checkbox"]').change(function () {
//                var count = $('input[type="checkbox"]:checked').size();
//                    var count = [];
//                    function counter() {
//                    count++;
//                    alert(count);
//                        $('.place').addAttr('disabled');
//                    }

//                $('.place').on('click', function () {
//                    count.push($('.place').attr('id'));
//                    alert(count);
////                    $('.place').addAttr('disabled');
//                });
//                alert(count);
//                if (count >= 2) {
//                    $('#start-game').removeClass('not-active');
//                } else if (count <= 1) {
//                    $('#start-game').addClass('not-active');
//                    setTimeout(function () {
//                       window.location.href = $('#start-game').attr('href');
//                    }, 5000);
//                }
//            });
        };
    </script>
@endsection