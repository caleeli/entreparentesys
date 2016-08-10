<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSynonymsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('synonyms', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('associated_value_id');
            //$table->foreign('associated_value_id')->references('id')->on('associated_values');
            $table->string('synonym')->unique();
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
        Schema::table('synonyms', function (Blueprint $table) {
            $table->dropForeign('synonyms_associated_value_id_foreign');
        });
        Schema::drop('synonyms');
    }
}
