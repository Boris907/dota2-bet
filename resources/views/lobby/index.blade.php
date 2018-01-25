@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1 text-center">
                <h1>Your current Game ID: {{$player_id}}</h1>
                <h2>You are in lobby</h2>
            </div>
            <div class="col-md-12 col-sm-6">
                {{--<form id="start_form" class="form-horizontal" method="post" action="#">--}}
                    {{--<meta name="csrf-token" content="{{ csrf_token() }}">--}}
{{--                    {{csrf_field()}}--}}
                    {{--<div class="col-md-5">--}}
                        {{--<div class="form-group">--}}
                            {{--<select name="right" class="form-control">--}}
                                {{--<input type="text">--}}
                            {{--</select>--}}
                            {{--<input type="text" id="id" name="username" class="form-control">--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="col-md-5">--}}
                        {{--<div id="demo"></div>--}}
                    {{--</div>--}}
                        {{--<div class="form-group">--}}
                            {{--<select name="right" class="form-control">--}}
                                {{--<option selected>2</option>--}}
                                {{--<option value="2">Choose place</option>--}}
                            {{--</select>--}}
                        {{--</div>--}}

                        {{--<div class="form-group">--}}
                            {{--<select name="right" class="form-control">--}}
                                {{--<option selected>3</option>--}}
                                {{--<option value="3">Choose place</option>--}}
                            {{--</select>--}}
                        {{--</div>--}}

                        {{--<div class="form-group">--}}
                            {{--<select name="right" class="form-control">--}}
                                {{--<option selected>4</option>--}}
                                {{--<option value="4">Choose place</option>--}}
                            {{--</select>--}}
                        {{--</div>--}}

                        {{--<div class="form-group">--}}
                            {{--<select name="right" class="form-control">--}}
                                {{--<option selected>5</option>--}}
                                {{--<option value="5">Choose place</option>--}}
                            {{--</select>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="col-md-5 col-md-push-1">--}}
                        {{--<div class="form-group">--}}
                            {{--<select name="left" class="form-control">--}}
                                {{--<option selected>6</option>--}}
                                {{--<option value="6">Choose place</option>--}}
                            {{--</select>--}}
                        {{--</div>--}}

                        {{--<div class="form-group">--}}
                            {{--<select name="left" class="form-control">--}}
                                {{--<option selected>7</option>--}}
                                {{--<option value="7">Choose place</option>--}}
                            {{--</select>--}}
                        {{--</div>--}}

                        {{--<div class="form-group">--}}
                            {{--<select name="left" class="form-control">--}}
                                {{--<option selected>8</option>--}}
                                {{--<option value="8">Choose place</option>--}}
                            {{--</select>--}}
                        {{--</div>--}}

                        {{--<div class="form-group">--}}
                            {{--<select name="left" class="form-control">--}}
                                {{--<option selected>9</option>--}}
                                {{--<option value="9">Choose place</option>--}}
                            {{--</select>--}}
                        {{--</div>--}}

                        {{--<div class="form-group">--}}
                            {{--<select name="left" class="form-control">--}}
                                {{--<option selected>10</option>--}}
                                {{--<option value="10">Choose place</option>--}}
                            {{--</select>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="col-md-4 col-md-push-5">--}}
                        {{--<button type="submit" class="btn btn-primary">Let`s Go</button>--}}
                    {{--</div>--}}
                {{--</form>--}}
                <div id="demo"></div>
                <button type="button" onclick="loadDoc()">go</button>
                <a href="{{ url('/lobby/1') }}">Test</a>
            </div>
        </div>
    </div>
@endsection
