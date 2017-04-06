<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RedefineCategoryCollection extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('categories', function (Blueprint $table) {
        if (Schema::hasColumn('categories', 'collections')) {
          $table->dropColumn('collections');
        }
        $table->integer('created_by')->unsigned();
        $table->integer('updated_by')->unsigned();
      });

      Schema::create('categories_collections', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('category_id')->unsigned();
        $table->string('name');
        $table->mediumText('description');
        $table->integer('created_by')->unsigned();
        $table->integer('updated_by')->unsigned();
        $table->timestamps();
      });

      Schema::create('categories_collections_media', function (Blueprint $table) {
        $table->integer('category_collection_id')->unsigned();
        $table->integer('media_id')->unsigned();
        $table->integer('sort_order')->unsigned();
        $table->timestamps();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('categories', function (Blueprint $table) {
        if (Schema::hasColumn('created_by', 'categories')) {
          $table->dropColumn('created_by');
        }
        if (Schema::hasColumn('updated_by', 'categories')) {
          $table->dropColumn('updated_by');
        }
      });
      Schema::drop('categories_collections');
      Schema::drop('categories_collections_media');
    }
}
