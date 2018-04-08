@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1 text-center">
            <h2>Choose your skill level</h2>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Newbei</div>
                <div class="panel-body">
                  <a href="rooms/list/newbie">Вход</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Ordinary</div>
                <div class="panel-body">
                    <a href="rooms/list/ordinary">Вход</a>
                </div>
            </div>
        </div>
    </div><div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Expert</div>
                <div class="panel-body">
                    <a href="rooms/list/expert">Вход</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection