<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDimensionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dimensions', function (Blueprint $table) {
            $table->increments('id');
            //$table->integer('variable_id');
            //$table->foreign('variable_id')->references('id')->on('statistical_variables');
            $table->string('type')->nullable();
            $table->string('name')->unique();
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
//        Schema::table('dimensions', function (Blueprint $table) {
//            $table->dropForeign('dimensions_variable_id_foreign');
//        });
        Schema::drop('dimensions');
    }
}
