<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CleanupCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('collections', function (Blueprint $table) {
        if (Schema::hasColumn('collections', 'is_copy')) {
          $table->dropColumn('is_copy');
        }
        if (Schema::hasColumn('collections', 'parent_id')) {
          $table->dropColumn('parent_id');
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
