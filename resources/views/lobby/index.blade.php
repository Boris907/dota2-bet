@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1 text-center">
                <h3>You are in lobby</h3>
                @if(session()->get('bet'))
                <h3 id="bet">Your current bet:{{session()->get('bet')}}$</h3>
                @else
                    <h3 id="bet">Your current bet:{{session()->get('min_bet')}}$</h3>
                @endif
                <h3 id="max">Max bet in this room:{{session()->get('max_bet')}}$</h3>
                <h3 id="bank">Current bank in this room:{{session()->get('bank')}}$</h3>
                <button id="change" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">Increase
                    your bet
                </button>
                <a href="{{url('/lobby/'.request()->session()->get('min_bet').'/reset')}}" id="reset"
                   class="btn btn-primary">Reset</a>
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
                        <li class="list-group-item"><a href="/rooms/lobby/{{$game_id}}/place/{{$place_id}}">Take place</a></li>
                    @else
                        <li class="list-group-item">{{$playerID['uid']}}</li>
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
                        <li class="list-group-item"><a href="/rooms/lobby/{{$game_id}}/place/{{$place_id}}">Take place</a></li>
                    @else
                        <li class="list-group-item">{{$playerID['uid']}}</li>
                    @endif
                @endforeach
            </ul>
        </div>
        <a class="btn btn-success" href="/rooms/lobbi/{{$game_id}}/start">Start game</a>
    </div>
    <div class="go"></div>

@endsection
