@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Your profile</div>

                <div class="panel-body">
                    Hi {{$user_info->name}}. It's your profile page.
                    <br>Your id {{$user_info->id}}.
                    <br>Your e-mail {{$user_info->email}}.
                    @if($user_info->player_id != 0)
                    <br>Your game ID {{$user_info->player_id}}
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

                    <!-- New Form -->
                    <form action="{{ url('personal') }}" method="POST" class="form-horizontal">
                        {{ csrf_field() }}
                      <div class="form-group">
                        <select name="service" class="custom-select custom-select-lg mb-3">
                          <option selected>Select service</option>
                            @foreach($services as $item)
                                <option value="{{$item['title']}}">{{$item['title']}}</option>
                            @endforeach
                        </select>
                      </div>
                      <div class="form-group">
                        <select name="game" class="custom-select custom-select-lg mb-3">
                          <option selected>Select game</option>
                            @foreach($games as $item)
                            <option value="{{$item['title']}}">{{$item['title']}}</option>
                            @endforeach
                        </select>
                      </div>
                      <div class="form-group">
                           <label for="player_id" class="col-sm-3 control-label">Steam ID</label>
                       
                           <div class="col-sm-6">
                               <input type="text" name="player_id" id="player_id" class="form-control">
                           </div>
                       </div>
                      <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-6">
                          <button type="submit" class="btn btn-default">
                          <i class="fa fa-btn fa-plus"></i>Add ID
                          </button>
                        </div>
                      </div>
                    </form>

                     <form action="{{ url('auth/steam') }}" method="get" class="form-horizontal">
                     <!-- Sign up to Steam -->
                         <div class="form-group">
                             <div class="col-sm-offset-3 col-sm-6">
                                 <button type="submit" class="btn btn-primary"><i class="fa fa-btn fa-plus"></i>Sign up to Steam</button>
                             </div>
                         </div>
                     </form>
                    </div>
            </div>        
        </div>
    </div>
</div>
@endsection
