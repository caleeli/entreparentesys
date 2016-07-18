<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SampleDataComercializacion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rep_comercializacion', function (Blueprint $table) {
            $table->string('variable_estadistica');
            $table->double('cantidad');
            $table->string('d4_zona_comercial');
            $table->string('d5_distrito_comercial');
            $table->string('d2_region_departamento');
            $table->string('d1_mes');
            $table->string('d1_anio');
            $table->string('d3_unidad_medida');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('rep_comercializacion');
    }
}
