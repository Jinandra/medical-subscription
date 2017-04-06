<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoryUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('category_user', function (Blueprint $table) {
        $table->integer('user_id')->unsigned();
        $table->integer('category_id')->unsigned();

        $table->foreign('user_id')->references('id')->on('users');
        $table->foreign('category_id')->references('id')->on('categories');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::drop('category_user');
    }
}
