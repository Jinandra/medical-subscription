<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLikeDislikeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	Schema::create('like_dislike',function (Blueprint $table){
				$table->increments('id');
				$table->string('email');
				$table->integer('id_media');
				$table->boolean('like');
				$table->boolean('dislike');
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
        Schema::drop('like_dislike');
    }
}
