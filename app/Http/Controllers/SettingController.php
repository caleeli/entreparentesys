<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class SettingController extends Controller
{
    /**
     * Show the application pivot table.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('setting.setting');
    }
}
