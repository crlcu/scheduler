<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAcceptUnsubscribeToTaskNotifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('task_notifications', function (Blueprint $table) {
            $table->tinyInteger('accept_unsubscribe')
                ->default(0)
                ->after('slack_config_json');
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
            $table->dropColumn('accept_unsubscribe');
        });
    }
}
