<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use DB;
use App\Model\Report as ReportBase;

class PivotController extends Controller
{
    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application pivot table.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pivotTable.pivot');
    }

    /**
     * Show the application pivot table import csv.
     *
     * @return \Illuminate\Http\Response
     */
    public function importCsv()
    {
        return view('pivotTable.importCsv');
    }

    public function pivotData($table)
    {
        $report = ReportBase::where('name', $table)->first()->toArray();
        $first = DB::table($report['table_name'])->first();
        $datos = DB::table($report['table_name'])->get();
//            ->where('variable_estadistica', $first->variable_estadistica)->get();
        return response()->json($datos);
    }
}
