<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBasicCollectionMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('basic_collection_media', function (Blueprint $table) {
          $table->integer('basic_collection_id')->unsigned();
          $table->integer('media_id')->unsigned();
          $table->foreign('basic_collection_id')->references('id')->on('basic_collections');
          $table->foreign('media_id')->references('id')->on('media');
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
    }
}
