<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
			Schema::create('email_history',function (Blueprint $table) {
				$table->increments('id');
				$table->integer('user_id');
				$table->string('email_destination');
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
        Schema::drop('email_history');
    }
}
