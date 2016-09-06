<?php

namespace App;

use Maatwebsite\Excel\Files\ExcelFile;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Collections\RowCollection;
use Maatwebsite\Excel\Collections\CellCollection;
use App\Model\StatisticalVariable;
use App\Model\SharedVariable;
use App\Model\AssociatedValue;
use App\Model\Dimension;
use App\Model\Report;
use App\Model\Folder;
use Schema;
use Illuminate\Database\Schema\Blueprint;
use Auth;
use DB;

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

    /**
     *
     * @var AssociatedValue[]
     */
    public $associatedValues = [];
    public $originalName;
    public $filename;
    /**
     *
     * @return type
     * @throws Exception
     */
    public function getFile()
    {
        /* @var $request Request */
        $request = \App::make('request');
        $destinationPath = storage_path().'/excels';
        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            $extension = $request->file('file')->getClientOriginalExtension();
            switch ($extension) {
                case 'xls':
                case 'xlsx':
                case 'csv':
                    $this->originalName = $request->file('file')->getClientOriginalName();
                    $filename = uniqid('file').'.'.$extension;
                    $request->file('file')->move($destinationPath, $filename);
                    $this->filename = $filename;
                    return $destinationPath.'/'.$filename;
                default:
                    throw new Exception(trans('labels.not_excel_file'));
            }
        } elseif($request->has('filename') && file_exists("$destinationPath/".$request->get('filename')) ) {
            return $destinationPath.'/'.$request->get('filename');
        }
        dd($request->has('filename'), "$destinationPath/".$request->get('filename'));
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

    public function saveVariablesDimensions($reportName)
    {
        /* @var $folder Folder */
        /* @var $variable StatisticalVariable */
        /* @var $share SharedVariable */
        $folder = Folder::firstOrNew([
            'name' => $reportName,
        ]);
        if(!$folder->exists) {
            $folder->parent_id = 0;
            $folder->owner_id = Auth::user() ? Auth::user()->id : 1;Auth::user() ? Auth::user()->id : 1;
            $folder->save();
        }
        foreach ($this->variables as $variable) {
            if (!$variable->exists) {
                $variable->save();
            }
            foreach($variable->shares()->where('type', 'OWNER')->get() as $share) {
                $share->folder_id = $folder->id;
                $share->save();
            }
        }
        foreach ($this->dimensions as $dimension) {
            if (!$dimension->exists) {
                $dimension->save();
            }
        }
        return $folder->id;
    }

    public function createReport($reportName, $folder_id, $replace = true)
    {
        /* @var $res \Maatwebsite\Excel\Collections\SheetCollection */
        /* @var $report Report */
        $res = $this->get();
        $report = Report::firstOrNew([
                'name'     => $reportName,
                'owner_id' => Auth::user()->id,
        ]);
        $report->folder_id = $folder_id;
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
                //$table->timestamps();
            });
        }
    }

    public function loadAssociatedValues()
    {
        $res = $this->get();
        $res->first(function($sheetNumber, RowCollection $sheet) {
            $sheet->each(function(CellCollection $row, $rowKey) {
                error_log("loadAssociatedValues: $rowKey");
                $i = 0;
                $variableName = null;
                $row->each(function($value, $key) use (&$i, &$variableName) {
                    switch ($i) {
                        case 0:
                            $variableName = $value;
                            break;
                        case 1:
                            break;
                        default:
                            $dimensionName = $key;
                            if (!empty($dimensionName) && !isset($this->associatedValues[$dimensionName][$value])) {
                                @$this->associatedValues[$dimensionName][$value]
                                    = AssociatedValue::firstOrNew(
                                        [
                                            'dimension_id' => $this->dimensions[$dimensionName]->id,
                                            'value'        => $value,
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

    public function saveAssociatedValues()
    {
        foreach ($this->associatedValues as $associatedValues1) {
            foreach ($associatedValues1 as $associatedValue) {
                if (!$associatedValue->exists) {
                    $associatedValue->save();
                }
            }
        }
    }

    public function loadData()
    {
        $res = $this->get();
        $res->first(function($sheetNumber, RowCollection $sheet) {
            $sheet->each(function(CellCollection $row, $rowNumber) {
                error_log("loadData: $rowNumber");
                $i = 0;
                $variableName = null;
                $reportRow = [];
                $row->each(function($value, $key) use (&$i, &$variableName, &$reportRow, $rowNumber) {
                    switch ($i) {
                        case 0:
                            $variableName = $value;
                            $reportRow[ImportExcel::VARIABLE_COLUMN_NAME] = $value;
                            break;
                        case 1:
                            if(!is_numeric($value)) {
                                throw new Exception("$key ($value) no tiene un valor numerico en la fila ".($rowNumber+2));
                            }
                            $reportRow[ImportExcel::VAlUE_COLUMN_NAME] = (double) $value;
                            break;
                        default:
                            $dimensionName = $key;
                            if (!empty($dimensionName)) {
                                $reportRow[$dimensionName] = $value;
                            }
                    }
                    $i++;
                });
                DB::table($this->report->table_name)->insert($reportRow);
                return true;
            });
        });
    }
}
