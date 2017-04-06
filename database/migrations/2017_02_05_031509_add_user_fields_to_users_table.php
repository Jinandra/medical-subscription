<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('users', function ($table) {
        $table->dropColumn('user_role');
        $table->integer('verified_by')->unsigned();
        $table->integer('declined_by')->unsigned();
        $table->integer('created_by')->unsigned();
        $table->integer('updated_by')->unsigned();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('users', function ($table) {
        $table->string('user_role');
        $table->dropColumn('verified_by');
        $table->dropColumn('declined_by');
        $table->dropColumn('created_by');
        $table->dropColumn('updated_by');
      });
    }
}
