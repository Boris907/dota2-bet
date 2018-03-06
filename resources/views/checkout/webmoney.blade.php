@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-sm-6 col-md-4 col-md-offset-4 col-sm-offset-3">
            <h1 class="text-center">Webmoney checkout</h1>
            <img class="img-responsive col-xs-offset-4" src="{{'/04.jpg'}}" width="150px">
            <br>
            <h4 class="text-center">Money</h4>
            <div class="col-md-3 col-md-offset-3">
                <input type="text" id="web_money" class="form-control" value="0">
            </div>
            <button class="btn btn-success" id="wm-submit" type="submit">Pay now</button>
            <br>
            <br>
            <div class="col-md-offset-1" id="wm-form">
            </div>
        </div>
    </div>
@endsection
