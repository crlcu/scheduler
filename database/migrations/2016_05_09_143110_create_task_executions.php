<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaskExecutions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_executions', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('task_id')->unsigned();
            $table->foreign('task_id')->references('id')->on('tasks');

            $table->string('status');
            $table->longText('result');

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
        Schema::drop('task_executions');
    }
}
