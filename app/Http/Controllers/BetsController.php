<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class BetsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

}
