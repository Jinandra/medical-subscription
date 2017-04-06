<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePseudoCollections extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pseudo_collections', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('user_id');
          $table->string('name');
          $table->boolean('is_pin');
          $table->mediumText('description');
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
        Schema::drop('pseudo_collections');
    }
}
