@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="dropdown">
                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="true">
                    Rooms filter
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                    <li><a href="?asc=1">Filter by bet`s(asc)</a></li>
                    <li><a href="?desc=1">Filter by bet`s(desc)</a></li>
                    <li><a href="#">Filter by free places</a></li>
                </ul>
            </div>
            <div class="col-md-10 col-md-offset-1 text-center">
                <h1>Game ID: {{$id_player}}</h1>
                <h2>Money: {{$coins}}$</h2>
                <h2>Choose the room where you want to play</h2>
            </div>
        </div>

        @if(array_key_exists('desc', $_GET))
            @foreach($desc as $room)
                <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                        <div class="panel panel-default">
                            <div class="panel-heading">{{ucfirst($room['room_rank'])}}
                                - {{' Min bet: $'. $room['min_bet']}}</div>
                            <div class="panel-body">
                                <a href="{{$room['url']}}">Enter</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            @foreach($asc as $room)
                <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                        <div class="panel panel-default">
                            <div class="panel-heading">{{ucfirst($room['room_rank'])}}
                                - {{' Min bet: $'. $room['min_bet']}}</div>
                            <div class="panel-body">
                                <a href="{{$room['url']}}">Enter</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
@endsection
