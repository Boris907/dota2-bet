@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1 text-center">
            <h1>Your current Game ID: {{$player_id}}</h1>
            <h2>Choose the room where you want to play</h2>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Room 1</div>
                <div class="panel-body">
                  <a href="lobby">Вход</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Room 2</div>
                <div class="panel-body">
                  Линка
                </div>
            </div>
        </div>
    </div><div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Room 3</div>
                <div class="panel-body">
                  Линка
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
