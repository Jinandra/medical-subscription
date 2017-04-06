<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media', function (Blueprint $table) {
            $table->increments('id');
						$table->string('title');
						$table->string('description');
						$table->json('tag');
						//$table->timestamps('submission_date');
						$table->integer('view_count');
						$table->string('email');
            $table->string('web_link');
						$table->enum('type',['video','text','image']);
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
        Schema::drop('media');
    }
}
