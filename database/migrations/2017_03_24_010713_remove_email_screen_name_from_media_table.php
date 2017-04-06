<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveEmailScreenNameFromMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('media', function (Blueprint $table) {
        if (Schema::hasColumn('media', 'email')) {
          $table->dropColumn('email');
        }
        if (Schema::hasColumn('media', 'screen_name')) {
          $table->dropColumn('screen_name');
        }
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
