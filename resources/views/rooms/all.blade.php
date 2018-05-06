@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1 text-center">
            <h2>All newbei rooms:</h2>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">List</div>
                <div class="panel-body">
                <ul>
                @foreach($lobbies as $room)
                <li>Game №{{key($room)}} || Min Bet {{$room[key($room)]['min_bet']}}||
                        Max Bet {{$room[key($room)]['max_bet']}} || Bank {{$room[key($room)]['bank']}}
                         <a href="../lobby/{{key($room)}}">Enter</a></li>
                @endforeach
                </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection