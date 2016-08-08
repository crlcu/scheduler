<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTaskNotificationsToHandleSlackAndMail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('task_notifications', function (Blueprint $table) {
            $table->string('type');
            $table->text('to')->after('status');
            $table->text('slack_config_json')->after('to');

            $table->dropColumn('email');
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
            $table->string('email')->after('status');

            $table->dropColumn('type');
            $table->dropColumn('to');
            $table->dropColumn('slack_config_json');
        });
    }
}
