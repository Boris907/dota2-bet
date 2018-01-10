@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Your profile</div>

                <div class="panel-body">
                    Hi {{$uInfo->name}}. It's your profile page. 
                    <br>Your id {{$uInfo->id}}.
                    <br>Your e-mail {{$uInfo->email}}.
                    @if($playerID != 0)
                    <br>Your game ID {{$playerID}}
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
                    <div class="dropdown">
                      <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        Select game
                        <span class="caret"></span>
                      </button>
                      <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                        @foreach ($allGames as $gameTitle)
                        <li><a href="#">{{$gameTitle->title}}</a></li>
                        @endforeach
                        <li role="separator" class="divider"></li>
                        <li><a href="#">Separated link</a></li>
                      </ul>
                    </div>
                       <!--  <div class="form-group">
                           <label for="player_id" class="col-sm-3 control-label">Steam ID</label>
                       
                           <div class="col-sm-6">
                               <input type="text" name="player_id" id="player_id" class="form-control">
                           </div>
                       </div>
                       
                       Add Button
                       <div class="form-group">
                           <div class="col-sm-offset-3 col-sm-6">
                               <button type="submit" class="btn btn-default">
                                   <i class="fa fa-btn fa-plus"></i>Add ID
                               </button>
                           </div>
                       </div> -->
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
