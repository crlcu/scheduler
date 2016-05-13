<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOneTimeOnlyToTasks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->tinyInteger('is_one_time_only')->default(0)->after('command');
            $table->tinyInteger('is_via_ssh')->default(0)->after('next_due');
            $table->text('ssh_config_json')->after('is_via_ssh');

            $table->dropColumn('viaSSH');
            $table->dropColumn('jsonSSH');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->tinyInteger('viaSSH')->default(0)->after('command');
            $table->text('jsonSSH')->after('viaSSH');

            $table->dropColumn('is_one_time_only');
            $table->dropColumn('is_via_ssh');
            $table->dropColumn('ssh_config_json');
        });
    }
}
