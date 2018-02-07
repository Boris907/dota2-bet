@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1 text-center">
                <h2>You are in lobby</h2>
                <h2 id="money">Money: {{Auth::user()->coins}}$</h2>

                @if(session()->get('bet'))
                    <h2 id="res_bet">Your bet:{{session()->get('bet')}}$</h2>
                @else
                <h2 id="res_bet">Your current bet:{{session()->get('min_bet')}}$</h2>
                @endif
                <button id="change" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">Increase your bet</button>
                <a href="{{url('/lobby/'.request()->session()->get('min_bet').'/reset')}}" id="reset" class="btn btn-primary">Reset</a>
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
                @foreach($radiant as $key => $id)
                    @if($id == 0)
                        <li class="list-group-item"><a href="/lobby/team/{{$key}}">Take place</a></li>
                    @else
                        <li class="list-group-item">{{$id}}</li>
                    @endif
                @endforeach
            </ul>
        </div>
        <div class="card col-md-6">
            <div class="card-header">
                Dire
            </div>
            <ul class="list-group list-group-flush">
                @foreach($dire as $key => $id)
                    @if($id == 0)
                        <li class="list-group-item"><a href="/lobby/team/{{$key}}">Take place</a></li>
                    @else
                        <li class="list-group-item">{{$id}}</li>
                    @endif
                @endforeach
            </ul>
        </div>
        <a class="btn btn-success" href="{{url('/lobby/start')}}">Start game</a>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-push-8">
                @if(Session::has('flash_message'))
                    <div id="bet_success" class="alert alert-success">{{Session::get('flash_message')}}
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection
