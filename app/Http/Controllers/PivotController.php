<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;

class PivotController extends Controller
{
    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application pivot table.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pivot');
    }
}
