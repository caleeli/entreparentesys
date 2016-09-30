<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssociatedValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('associated_values', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('dimension_id');
            //$table->foreign('dimension_id')->references('id')->on('dimensions');
            $table->string('value')->nullable();
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
        Schema::table('associated_values', function (Blueprint $table) {
            $table->dropForeign('associated_values_dimension_id_foreign');
        });
        Schema::drop('associated_values');
    }
}
