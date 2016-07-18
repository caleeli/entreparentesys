<?php

use Illuminate\Database\Seeder;

class RepComercializacionSeeder extends Seeder
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
            $row['cantidad'] = (double) $row['cantidad'];
            DB::table('rep_comercializacion')->insert($row);
        }
    }
}
