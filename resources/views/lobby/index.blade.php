@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1 text-center">
            <h1>Your current Game ID: {{$id_player}}</h1>
            <h2>You are in lobby</h2>
            <a class="btn btn-success" href="{{url('/lobby/start')}}">Start game</a>
        </div>
    </div>
</div>
@endsection
