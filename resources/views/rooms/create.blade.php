@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1 text-center">
            <h1>Your ID: {{Auth::user()->player_id}}</h1>
            <h2>Choose the room where you want to play</h2>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">1 x 1</div>
                <div class="panel-body">
                  <a href="new_room/set/2">Create</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">2 x 2</div>
                <div class="panel-body">
                  <a href="new_room/set/4">Create</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">3 x 3</div>
                <div class="panel-body">
                  <a href="new_room/set/6">Create</a>
                </div>
            </div>
        </div>
    </div>
        <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">4 x 4</div>
                <div class="panel-body">
                  <a href="new_room/set/8">Create</a>
                </div>
            </div>
        </div>
    </div>
        <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">5 x 5</div>
                <div class="panel-body">
                  <a href="new_room/set/10/newbie">Create</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
