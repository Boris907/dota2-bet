<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Start</title>

    <!-- FontsS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet'
          type='text/css'>
    <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700" rel='stylesheet' type='text/css'>

    <!-- Styles -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    {{-- <link href="{{ elixir('css/app.css') }}" rel="stylesheet"> --}}

    <style>
        body {
            font-family: 'Lato';
        }

        .fa-btn {
            margin-right: 6px;
        }
    </style>
</head>
<body id="app-layout">
<nav class="navbar navbar-default">
    <div class="container">
        <div class="navbar-header">

            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#app-navbar-collapse">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Branding Image -->
            <a class="navbar-brand" href="{{ url('/') }}" onclick="refresh()">
                Home
            </a>
            <a class="navbar-brand" href="{{ url('personal') }}" onclick="refresh()">
                Personal info
            </a>
            <a class="navbar-brand" href="{{ url('stats') }}" onclick="refresh()">
                Stats
            </a>
            <a class="navbar-brand" href="{{ url('rooms') }}" onclick="refresh()">
                Choose room
            </a>
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->
            <ul class="nav navbar-nav">
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
                <!-- Authentication Links -->
                @if (Auth::guest())
                    <li><a href="{{ url('/login') }}">Login</a></li>
                    <li><a href="{{ url('/register') }}">Register</a></li>
                @else
                    <li id="cash"><a href="#" id="cash_val" data-toggle="modal" data-target="#exampleStripe"><span
                                    class="glyphicon glyphicon-plus"></span> Cash: ${{Auth::user()->coins}}</a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-center" id="exampleModalLabel">Increase your bet</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                @if(session()->get('bet'))
                    <h4 class="min text-center">Your current bet: {{session()->get('bet')}}$</h4>
                @else
                    <h4 class="min text-center">Your current bet:{{session()->get('min_bet')}}$</h4>
                @endif
            </div>
            <div class="modal-body">
                <form action="#" method="post">
                    <meta name="csrf-token" content="{{ csrf_token() }}">
                    <div class="input-group col-md-12">
                        <label style="padding:12px ">Increase bet for: </label>
                        <button type="button" style="margin:5px" class="btn btn-primary bet_submit" value="1">1$
                        </button>
                        <button type="button" style="margin:5px" class="btn btn-primary bet_submit" value="2">2$
                        </button>
                        <button type="button" style="margin:5px" class="btn btn-primary bet_submit" value="5">5$
                        </button>
                        <button type="button" style="margin:5px" class="btn btn-primary bet_submit" value="10">10$
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="exampleStripe" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-center" id="exampleModalLabel">Refill your cash</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-offset-2">
                    <a href="{{ url('/checkout/stripe') }}"><img src="{{'/images.png'}}" width="120px;" alt=""></a>
                    <a href="{{ url('/checkout/g2a') }}"><img src="{{'/2303.png'}}" width="120px;" alt=""></a>
                    <a href="{{ url('/checkout/webmoney') }}"><img src="{{'/04.jpg'}}" width="120px;" alt=""></a>
                </div>
            </div>
        </div>
    </div>
</div>

@yield('content')


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script src="/js/main.js"></script>
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script type="text/javascript" src="{{ url('/js/checkout.js') }}"></script>

</body>
</html>
