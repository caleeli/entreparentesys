<?php

use Illuminate\Database\Seeder;
use App\Model\StatisticalVariable;
use App\Model\AssociatedValue;
use App\Model\Dimension;

class RepComercializacionVarDims extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jsonFile = __DIR__.'/../../public/comercializacion.json';
        $json = json_decode(file_get_contents($jsonFile));
        foreach ($json as $row) {
            $row = (array) $row;
            $variable = StatisticalVariable::firstOrNew(
                [
                    'name' => $row['variable_estadistica'],
                ]
            );
            $variable->name = $row['variable_estadistica'];
            $variable->save();
            foreach($row as $dimensionName => $dimensionValue) {
                if ($dimensionName === 'variable_estadistica'
                    || $dimensionName === 'cantidad'
                ) {
                    continue;
                }
                $dimension = Dimension::firstOrNew(
                    [
                        'name' => $dimensionName,
                        //'variable_id' => $variable->id,
                    ]
                );
                $dimension->name = $dimensionName;
                //$dimension->variable_id = $variable->id;
                $dimension->save();
                $associatedValue = AssociatedValue::firstOrNew(
                    [
                        'dimension_id' => $dimension->id,
                        'value' => $dimensionValue,
                    ]
                );
                $associatedValue->dimension_id = $dimension->id;
                $associatedValue->value = $dimensionValue;
                $associatedValue->save();
            }
        }
    }
}
