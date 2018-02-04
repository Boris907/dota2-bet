@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1 text-center">
            <h2>You are in lobby</h2>
            <h2>Money: {{$coins}}$</h1>
            <h2>Your bet: {{$bet}}$</h1>
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
    <li class="list-group-item"><a href="/lobby/team/{{$key}}">Take place</a> </li>
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
    <li class="list-group-item"><a href="/lobby/team/{{$key}}">Take place</a> </li>
    @else
    <li class="list-group-item">{{$id}}</li>
    @endif
    @endforeach
  </ul>
</div>
            <a class="btn btn-success" href="{{url('/lobby/start')}}">Start game</a>
</div>

@endsection
