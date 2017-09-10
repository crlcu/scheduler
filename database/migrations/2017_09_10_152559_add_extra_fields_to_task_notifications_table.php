<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExtraFieldsToTaskNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('task_notifications', function (Blueprint $table) {
            $table->string('condition')
                ->nullable()
                ->after('accept_unsubscribe');
            $table->string('value')
                ->nullable()
                ->after('condition');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $table->dropColumn('condition');
        $table->dropColumn('value');
    }
}
