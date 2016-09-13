<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ImportExcel;
use App\Xls2Csv2Db;

class ImportExcelController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('importExcel.create');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('importExcel.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        ini_set('memory_limit', -1);
        set_time_limit(-1);
        $destinationPath = storage_path().'/excels';
        $extension = $request->file('file')->getClientOriginalExtension();
        $filename = uniqid('file').'.'.$extension;
        $request->file('file')->move($destinationPath, $filename);
        try {
            $import = new Xls2Csv2Db;
            $import->originalName = $request->file('file')->getClientOriginalName();
            $import->filename = $filename;
            $import->load($destinationPath.'/'.$filename);
            return response()->json(
                    [
                    'reportName'       => explode('.', $import->originalName)[0],
                    'filename'         => $import->table_name,
                    'variables'        => array_values($import->variables),
                    'dimensions'       => array_values($import->dimensions),
                    'associatedValues' => $import->associatedValues,
                    ], 200
            );
        } catch (\Exception $err) {
            return response()->json($err->getMessage(), 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        ini_set('memory_limit', -1);
        set_time_limit(-1);
        try {
            $import = new Xls2Csv2Db;
            $import->reload($request->get('filename'));
            $reportName = $request->get('report_name');
            $import->saveReport($reportName);
        } catch (\Exception $err) {
            return response()->json($err->getMessage(), 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
