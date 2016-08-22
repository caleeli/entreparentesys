<?php

namespace App\Http\Controllers;

use DB;

class DataController extends Controller
{
    public function comercializacion(){
        $datos = DB::table('test157b625a043fbb')
            //->where('variable_estadistica','=','Comercializacion de  Diesel Oil Nacional')
            ->get();
        return response()->json($datos);
    }
}
