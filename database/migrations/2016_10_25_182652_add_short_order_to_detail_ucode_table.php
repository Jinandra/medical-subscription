<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddShortOrderToDetailUcodeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detail_ucode', function (Blueprint $table) {
            $table->smallInteger('sort_order')->after('id_media')->default(0)->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('detail_ucode', function (Blueprint $table) {
            $table->dropColumn('sort_order');
        });
    }
}
