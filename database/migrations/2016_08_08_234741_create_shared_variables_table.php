<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSharedVariablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shared_variables', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('statistical_variable_id')->unsigned()->nullable();
            $table->boolean('seen')->default(false);
            $table->enum('type', ['OWNER', 'SHARED', 'PUBLIC']);
            $table->integer('folder_id')->unsigned()->nullable();
            $table->timestamps();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->foreign('statistical_variable_id')
                ->references('id')
                ->on('statistical_variables')
                ->onDelete('cascade');
            $table->foreign('folder_id')
                ->references('id')
                ->on('folders')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('shared_variables');
    }
}
