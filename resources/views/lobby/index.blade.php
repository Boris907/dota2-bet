@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1 text-center">
            <h1>Your current Game ID: {{$uId}}</h1>
            <h2>You are in lobby</h2>
        </div>
    </div>
</div>
@endsection
