<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RedefineCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      if ( !Schema::hasColumn('categories', 'description') ) {
        Schema::table('categories', function (Blueprint $table) {
          $table->mediumText('description');
        });
      }
      if ( Schema::hasColumn('categories', 'deleted_at') ) {
        Schema::table('categories', function (Blueprint $table) {
          $table->dropColumn('deleted_at');
        });
      }
      if ( !Schema::hasTable('categories_media') ) {
        Schema::rename('categories_collections_media', 'categories_media');
      }
      if (Schema::hasColumn('categories_media', 'category_collection_id')) {
        Schema::table('categories_media', function (Blueprint $table) {
          $table->renameColumn('category_collection_id', 'category_id');
        });
      }
      if ( !Schema::hasColumn('categories_media', 'id') ) {
        Schema::table('categories_media', function (Blueprint $table) {
          $table->increments('id');
        });
      }
      DB::table('categories_media')->truncate();
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
