<?php

namespace App;

use Maatwebsite\Excel\Files\ExcelFile;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Collections\RowCollection;
use Maatwebsite\Excel\Collections\CellCollection;
use App\Model\StatisticalVariable;
use App\Model\AssociatedValue;
use App\Model\Dimension;
use App\Model\Report;
use Schema;
use Illuminate\Database\Schema\Blueprint;
use Auth;

/**
 * Description of ImportExcel
 *
 * @author davidcallizaya
 */
class ImportExcel extends ExcelFile
{
    const VARIABLE_COLUMN_NAME = 'variable_estadistica';
    const VAlUE_COLUMN_NAME = 'valor';

    /**
     *
     * @var StatisticalVariable[]
     */
    public $variables = [];

    /**
     *
     * @var Dimension[]
     */
    public $dimensions = [];
    public $originalName;

    /**
     *
     * @return type
     * @throws Exception
     */
    public function getFile()
    {
        /* @var $request Request */
        $request = \App::make('request');
        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            $destinationPath = storage_path().'/excels';
            $extension = $request->file('file')->getClientOriginalExtension();
            switch ($extension) {
                case 'xls':
                case 'xlsx':
                case 'csv':
                    $this->originalName = $request->file('file')->getClientOriginalName();
                    $filename = uniqid('file').'.'.$extension;
                    $request->file('file')->move($destinationPath, $filename);
                    return $destinationPath.'/'.$filename;
                default:
                    throw new Exception(trans('labels.not_excel_file'));
            }
        }
        throw new Exception(trans('labels.failed_upload'));
    }

    public function readHeaders()
    {
        /* @var $res \Maatwebsite\Excel\Collections\SheetCollection */
        $res = $this->get();
        $res->first(function($sheetNumber, RowCollection $sheet) {
            $sheet->first(function($rowNumber, CellCollection $row) {
                $i = 0;
                $variableName = null;
                $row->each(function($value, $key) use (&$i, &$variableName) {
                    switch ($i) {
                        case 0:
                            $variableName = $value;
                            if (!isset($this->variables[$value])) {
                                $this->variables[$value] = StatisticalVariable::firstOrNew(
                                        [
                                            'name' => $variableName,
                                        ]
                                );
                            }
                            break;
                        case 1:
                            $this->variables[$variableName]->type = $key;
                            break;
                        default:
                            $dimensionName = $key;
                            if (!empty($dimensionName) && !isset($this->dimensions[$dimensionName])) {
                                $this->dimensions[$dimensionName] = Dimension::firstOrNew(
                                        [
                                            'name' => $dimensionName,
                                        ]
                                );
                            }
                    }
                    $i++;
                });
                return true;
            });
        });
    }

    public function saveVariablesDimensions()
    {
        foreach ($this->variables as $variable) {
            if (!$variable->exists) {
                $variable->save();
            }
        }
        foreach ($this->dimensions as $dimension) {
            if (!$dimension->exists) {
                $dimension->save();
            }
        }
    }

    public function createReport($reportName, $replace = true)
    {
        /* @var $res \Maatwebsite\Excel\Collections\SheetCollection */
        /* @var $report Report */
        $res = $this->get();
        $report = Report::firstOrNew([
                'name'     => $reportName,
                'owner_id' => Auth::user()->id,
        ]);
        $this->report = $report;
        if (!$report->exists) {
            $report->table_name = preg_replace('/[^a-z0-9]/i', '_',
                                               uniqid($reportName));
        }
        if (!$report->exists || $replace) {
            if (!$report->exists) {
                $report->save();
            }
            Schema::dropIfExists($report->table_name);
            Schema::create($report->table_name,
                           function (Blueprint $table) {
                $table->string(ImportExcel::VARIABLE_COLUMN_NAME);
                $table->double(ImportExcel::VAlUE_COLUMN_NAME);
                foreach ($this->dimensions as $dimension) {
                    $table->string($dimension->name);
                }
                $table->timestamps();
            });
        }
    }

    public function loadData()
    {
        $res = $this->get();
        $res->each(function(RowCollection $sheet) {
            $sheet->first(function($rowNumber, CellCollection $row) {
                $i = 0;
                $variableName = null;
                $reportRow = [];
                $row->each(function($value, $key) use (&$i, &$variableName, &$reportRow) {
                    switch ($i) {
                        case 0:
                            $variableName = $value;
                            $reportRow[ImportExcel::VARIABLE_COLUMN_NAME] = $value;
                            break;
                        case 1:
                            $reportRow[ImportExcel::VAlUE_COLUMN_NAME] = $value;
                            break;
                        default:
                            $dimensionName = $key;
                            $reportRow[$dimensionName] = $value;
                    }
                    $i++;
                });
                DB::table($this->report->table_name)->insert($row);
                return true;
            });
        });
    }
}
