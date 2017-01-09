<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOnlyResultToTaskNotifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('task_notifications', function (Blueprint $table) {
            $table->tinyInteger('only_result')->default(0)->after('with_result');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('task_notifications', function (Blueprint $table) {
            $table->dropColumn('only_result');
        });
    }
}
