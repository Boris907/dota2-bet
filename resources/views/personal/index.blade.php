@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Your profile</div>

                <div class="panel-body">
                    Hi {{$user_info->name}}. It's your profile page.
                        <br>Your id: {{$user_info->id}}.
                        <br>Your e-mail: {{$user_info->email}}.
                        <br>Your money: ${{$user_info->coins}}
                    @if(!empty($user_info->player_id))
                        <br>Your Steam ID: {{$user_info->player_id}}
                    @endif
                    @if(!empty($user_info->rate))
                        <br>Your average MMR in Dota2: {{$user_info->rate}}
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Add Game</div>

                 <div class="panel-body">
                    <!-- Display Validation Errors -->
                    @include('common.errors')
                     <div class="col-md-8">

                    <!-- New Form -->
                    <form action="{{ url('personal') }}" method="POST" class="form-horizontal">
                        {{ csrf_field() }}
                      <div class="form-group col-md-8">
                        <label for="service">Select service:</label>
                        <select class="form-control" name="service">
                          <option selected>Select service</option>
                            @foreach($services as $item)
                                <option value="{{$item['title']}}">{{$item['title']}}</option>
                            @endforeach
                        </select>
                      </div>

                      <div class="form-group col-md-8">
                        <label for="game">Select Game:</label>
                        <select class="form-control " name="game">
                          <option selected>Select game</option>
                            @foreach($games as $item)
                            <option value="{{$item['title']}}">{{$item['title']}}</option>
                            @endforeach
                        </select>
                      </div>
                      <div class="form-group col-md-8">
                           <label for="player_id" >Steam ID:</label>
                           <input type="text" name="player_id" id="player_id" class="form-control">
                       </div>
                      <div class="form-group">
                        <div class="col-sm-offset-0 col-sm-6">
                          <button type="submit" class="btn btn-default">
                          <i class="fa fa-btn fa-plus"></i>Add ID
                          </button>
                        </div>
                      </div>
                    </form>
                     <a class="btn btn-default" href="{{ url('personal/rate') }}">Update Dota2 MMR</a>
                     <br>
                     <br>
                     <form action="{{ url('auth/steam') }}" method="get" class="form-horizontal">
                     <!-- Sign up to Steam -->
                         <div class="form-group">
                             <div class="col-sm-offset-0 col-sm-6">
                                 <button type="submit" class="btn btn-primary"><i class="fa fa-btn fa-plus"></i>Sign up to Steam</button>
                             </div>
                         </div>
                     </form>
                    </div>
                 </div>
            </div>        
        </div>
    </div>
</div>
@endsection
