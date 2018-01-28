@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1 text-center">
                <h1>Your current Game ID: {{$id_player}}</h1>
                <h2>You have been invited in the game, good luck!</h2>
            </div>
        </div>
    </div>
@endsection
