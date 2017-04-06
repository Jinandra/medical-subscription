<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UcodeHistory extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        //
        if (Schema::hasTable('users') === false && Schema::hasColumn('id', 'ucode', 'email') === false) {
            Schema::create('ucode_history', function (Blueprint $table) {
                $table->increment('id');
                $table->string('ucode');
                $table->string('email');
                $table->timestamp();
                //foreign key
                $table->foreign('ucode')->references('ucode')->on('ucode');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        //
    }

}
