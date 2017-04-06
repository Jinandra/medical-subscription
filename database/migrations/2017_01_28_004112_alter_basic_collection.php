<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterBasicCollection extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
      Schema::drop('basic_collection_media');
      Schema::drop('basic_collections');

      Schema::create('basic_collection_media', function (Blueprint $table) {
        $table->increments('id');
        $table->string('media_id');
        $table->integer('sort_order')->unsigned();
        $table->timestamps();
        $table->integer('created_by')->unsigned();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::drop('basic_collection_media');

      Schema::create('basic_collections', function (Blueprint $table) {
        $table->increments('id');
        $table->string('name');
        $table->mediumText('description');
        $table->timestamps();
      });
      Schema::create('basic_collection_media', function (Blueprint $table) {
        $table->integer('basic_collection_id')->unsigned();
        $table->integer('media_id')->unsigned();
        $table->foreign('basic_collection_id')->references('id')->on('basic_collections');
        $table->foreign('media_id')->references('id')->on('media');
      });
    }
}
