@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1 text-center">
                <h2>You have been invited in the game, good luck!</h2>
        </div>
    </div>
     <div class="container">
        <div class="card col-md-6">
            <div class="card-header">
                Radiant
            </div>
            <ul class="list-group list-group-flush">
                @foreach($radiant as $key => $id)
                   <li class="list-group-item">{{$id}}</li>
                @endforeach
            </ul>
        </div>
        <div class="card col-md-6">
            <div class="card-header">
                Dire
            </div>
            <ul class="list-group list-group-flush">
                @foreach($dire as $key => $id)
                   <li class="list-group-item">{{$id}}</li>
                @endforeach
            </ul>
        </div>
        <a class="btn btn-success" href="results">Get score</a>
    </div>
@endsection
