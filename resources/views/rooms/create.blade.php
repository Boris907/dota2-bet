@extends('layouts.app')

@section('content')
<div class="container">
<form action="/new_room/set" method="POST">
{{csrf_field()}}
  <div class="form-group">
    <label for="players">Players</label>
    <select name="players" class="form-control" id="players">
      <option value="2">1x1</option>
      <option value="4">2x2</option>
      <option value="6">3x3</option>
      <option value="8">4x4</option>
      <option value="10" selected="selected">5x5</option>
    </select>
  </div>
    <div class="form-group">
    <label for="rank">Type</label>
    <select name="rank" class="form-control" id="rank">
      <option value="newbie">Newbie</option>
      <option value="ordinary">Ordinary</option>
      <option value="expert">Expert</option>
      <option value="private">Private</option>
    </select>
  </div>
   <button type="submit" class="btn btn-success">Create</button>
</form>

</div>
@endsection
