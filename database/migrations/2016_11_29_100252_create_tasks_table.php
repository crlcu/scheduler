<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');

            $table->string('name');
            $table->longText('command');
            $table->tinyInteger('is_one_time_only')->default(0);
            $table->string('cron_expression')->nullable();;
            $table->dateTime('next_due')->nullable();
            $table->tinyInteger('is_via_ssh')->default(0);
            $table->text('ssh_config_json')->nullable();;
            $table->tinyInteger('is_concurrent')->default(0);
            $table->tinyInteger('is_enabled')->default(0);
            $table->tinyInteger('needs_review')->default(0);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
