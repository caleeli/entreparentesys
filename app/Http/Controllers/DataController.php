<?php

namespace App\Http\Controllers;

use DB;

class DataController extends Controller
{
    public function comercializacion(){
        $datos = DB::table('rep_comercializacion')->where('variable_estadistica','=','Comercializacion de  Diesel Oil Nacional')->limit(100)->get();
        return response()->json($datos);
    }
}
