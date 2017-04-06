<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixTextHistoryColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('text_history', function (Blueprint $table) {
        if (Schema::hasColumn('text_history', 'ucode')) {
          $table->dropColumn('ucode');
        }
        if (!Schema::hasColumn('text_history', 'ucode_id')) {
          $table->integer('ucode_id')->unsigned()->after('user_id');
          $table->foreign('ucode_id')
            ->references('id')
            ->on('ucode')
            ->onUpdate('cascade')
            ->onDelete('cascade');
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
