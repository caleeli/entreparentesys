<?php

namespace App;

use DB;
use Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Model\StatisticalVariable;
use App\Model\SharedVariable;
use App\Model\AssociatedValue;
use App\Model\Dimension;
use App\Model\Report;
use App\Model\Folder;
use Auth;

/**
 * Description of Xls2Csv2Db
 * Require pyton
 *
 * @author davidcallizaya
 */
class Xls2Csv2Db
{
    const pyton = 'python';

    public $table_name = '';
    public $columns = [];

    /**
     *
     * @var Report $report
     */
    public $report;

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

    public function load($file)
    {
        $this->table_name = uniqid('tmp_');
        $filepath = realpath($file);
        $target = realpath(storage_path().'/excels').'/'.basename($filepath).'.csv';
        exec(
            static::pyton.
            ' '.
            base_path().
            '/xlsx2csv/xlsx2csv.py -d tab -e -i '.
            escapeshellarg($filepath).
            ' > '.
            escapeshellarg($target)
        );
        $handle = fopen($target, 'r');
        $line = substr(fgets($handle), 0, -1);
        fclose($handle);
        $this->columns = explode("\t", $line);
        foreach($this->columns as &$column) {
            $column = preg_replace('/[^a-z0-9]+/i', '_', $column);
        }
        $this->createTable();
        $sql = "COPY ".$this->table_name." FROM '$target' WITH DELIMITER '\t' CSV HEADER";
        DB::statement($sql);
        $this->loadVariables();
        $this->loadDimensions();
        $this->loadAssociatedValues();
    }

    public function reload($table_name)
    {
        $this->table_name = $table_name;
        $this->columns = Schema::getColumnListing($this->table_name);
        $this->loadVariables();
        $this->loadDimensions();
        $this->loadAssociatedValues();
    }

    protected function createTable()
    {
        Schema::create($this->table_name,
                       function (Blueprint $table) {
            $table->string($this->columns[0]);
            $table->double($this->columns[1]);
            foreach ($this->columns as $i => $column) {
                if ($i < 2) {
                    continue;
                }
                $table->string($column);
            }
        });
    }

    protected function loadVariables()
    {
        $variables = DB::table($this->table_name)->select($this->columns[0])->distinct()->get();
        foreach ($variables as $row) {
            $name = $this->columns[0];
            $value = $row->$name;
            $variableName = $value;
            if (!isset($this->variables[$value])) {
                $this->variables[$value] = StatisticalVariable::firstOrNew(
                        [
                            'name' => $variableName,
                        ]
                );
            }
            $this->variables[$variableName]->type = $this->columns[1];
        }
    }

    protected function loadDimensions()
    {
        foreach ($this->columns as $i => $dimensionName) {
            if ($i < 2) {
                continue;
            }
            if (!empty($dimensionName) && !isset($this->dimensions[$dimensionName])) {
                $this->dimensions[$dimensionName] = Dimension::firstOrNew(
                        [
                            'name' => $dimensionName,
                        ]
                );
            }
        }
    }

    protected function loadAssociatedValues()
    {
        for ($i = 2, $l = count($this->columns); $i < $l; $i++) {
            $dimensionName = $this->columns[$i];
            $query = DB::table($this->table_name)->select($dimensionName);
            $query->select($this->columns[$i]);
            $values = $query->distinct()->get();
            foreach ($values as $row) {
                $value = $row->$dimensionName;
                if (!empty($dimensionName) && !isset($this->associatedValues[$dimensionName][$value])) {
                    @$this->associatedValues[$dimensionName][$value] = AssociatedValue::firstOrNew(
                            [
                                'dimension_id' => $this->dimensions[$dimensionName]->id,
                                'value'        => $value,
                            ]
                    );
                }
            }
        }
    }

    protected function saveVariablesDimensions($reportName)
    {
        /* @var $folder Folder */
        /* @var $variable StatisticalVariable */
        /* @var $share SharedVariable */
        $folder = Folder::firstOrNew([
                'name' => $reportName,
        ]);
        if (!$folder->exists) {
            $folder->parent_id = 0;
            $folder->owner_id = Auth::user() ? Auth::user()->id : 1;
            Auth::user() ? Auth::user()->id : 1;
            $folder->save();
        }
        foreach ($this->variables as $variable) {
            if (!$variable->exists) {
                $variable->save();
            }
            foreach ($variable->shares()->where('type', 'OWNER')->get() as $share) {
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

    protected function saveAssociatedValues()
    {
        foreach ($this->associatedValues as $associatedValues1) {
            foreach ($associatedValues1 as $associatedValue) {
                if (!$associatedValue->exists) {
                    $associatedValue->save();
                }
            }
        }
    }

    public function saveReport($reportName, $replace = true)
    {
        /* @var $res \Maatwebsite\Excel\Collections\SheetCollection */
        /* @var $report Report */
        $report = Report::firstOrNew([
                'name'     => $reportName,
                'owner_id' => Auth::user()->id,
        ]);
        $folder_id = $this->saveVariablesDimensions($reportName);
        $this->saveAssociatedValues();
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
            Schema::rename($this->table_name, $this->report->table_name);
        }
    }

    public function deleteTable()
    {
        $sql = "DROP TABLE ".$this->table_name;
        DB::statement($sql);
    }
}
