<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ImportExcel;

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
    public function store(ImportExcel $import)
    {
        //try {
            /* @var $res \Maatwebsite\Excel\Collections\SheetCollection */
            set_time_limit(0);
            $import->readHeaders();
            $import->loadAssociatedValues();
            return response()->json(
                    [
                        'reportName' => explode('.', $import->originalName)[0],
                        'filename'   => $import->filename,
                        'variables'  => array_values($import->variables),
                        'dimensions' => array_values($import->dimensions),
                        'associatedValues' => $import->associatedValues,
                    ], 200
            );
        //} catch (\Exception $err) {
        //    return response()->json($err->getMessage(), 400);
        //}
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
    public function update(ImportExcel $import, Request $request, $id)
    {
        $import->readHeaders();
        $import->loadAssociatedValues();
        $import->saveVariablesDimensions();
        $import->createReport(explode('.', $request->get('report_name')));
        $import->saveAssociatedValues();
        $import->loadData();
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
