@extends('layouts.app')

@section('content')
<div class="container">
<h1 class="text-center">{{$exception->getMessage()}}</h1>
<h3 class="text-center"><a href="/personal">Личный кабинет</a></h3>
</div>
@endsection