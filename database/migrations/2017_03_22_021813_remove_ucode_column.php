<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveUcodeColumn extends Migration
{
    public function dropColumn ($tableName) {
      if (Schema::hasColumn($tableName, 'ucode')) {
        Schema::table($tableName, function (Blueprint $table) {
          $table->dropColumn('ucode');
        });
      }
    }
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      $this->dropColumn('ucode_history');
      $this->dropColumn('detail_ucode');
      $this->dropColumn('email_history');
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
