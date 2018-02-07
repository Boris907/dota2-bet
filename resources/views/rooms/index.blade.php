@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1 text-center">
            <h1>Game ID: {{$id_player}}</h1>
            <h2>Money: {{$coins}}$</h2>
            <h2>Choose the room where you want to play</h2>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Newbie (Min bet 2$)</div>
                <div class="panel-body">
                  <a href="lobby/newbie">Enter</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Ordinary (Min bet 4$)</div>
                <div class="panel-body">
                  <a href="lobby/ordinary">Enter</a>
                </div>
            </div>
        </div>
    </div><div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Expert (Min bet 10$)</div>
                <div class="panel-body">
                  <a href="lobby/expert">Enter</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
