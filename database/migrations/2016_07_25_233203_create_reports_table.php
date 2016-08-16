<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('table_name')->unique();
            $table->integer('folder_id')->nullable();
            $table->integer('owner_id');
            $table->timestamps();
            //$table->foreign('folder_id')->references('id')->on('folders');
            //$table->foreign('owner_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropForeign('reports_folder_id_foreign');
            $table->dropForeign('reports_owner_id_foreign');
        });
        Schema::drop('reports');
    }
}
