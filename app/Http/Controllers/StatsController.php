<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

}
