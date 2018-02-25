@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-sm-6 col-md-4 col-md-offset-4 col-sm-offset-3">
            <h1 class="text-center">Webmoney checkout</h1>
            <img class="img-responsive col-xs-offset-4" src="{{'/04.jpg'}}" width="150px">
            <br>
            <h4 class="text-center">Money</h4>
            <div class="col-md-4 col-md-offset-4">
            <input type="text" id="web_money" class="form-control" value="0">
            </div>
            <br>
            <br>
            <div class="col-md-offset-1">
                <script src="//merchant.webmoney.ru/conf/lib/wm-simple-x20.min.js?wmid=396850847264&purse=R251053037627&key=398146463&amount=0&desc=Покупка валюты"
                        id="wm-script"> </script>
            </div>
        </div>
    </div>
@endsection
