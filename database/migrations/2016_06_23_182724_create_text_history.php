<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTextHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
 			Schema::create('text_history',function (Blueprint $table) {
        $table->increments('id');
        $table->integer('user_id');
        $table->string('phone_number');
        $table->string('ucode');
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
			Schema::drop('text_history');        
    }
}
