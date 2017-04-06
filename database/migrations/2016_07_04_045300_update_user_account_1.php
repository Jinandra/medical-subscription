<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserAccount1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function ($table) {
            $table->string('birthday',15)->after('remember_token');
            $table->string('country',150)->after('birthday');
            $table->string('mobile_number',30)->after('country');
            $table->string('alt_phone_number',30)->after('mobile_number');
            $table->string('work_number',30)->after('alt_phone_number');
            $table->string('hometown',150)->after('work_number');
            $table->string('gender',10)->after('hometown');
            $table->boolean('set_friendlist')->after('gender');
            $table->boolean('notification')->after('set_friendlist');
            $table->boolean('accept_friend_request')->after('notification');
            $table->boolean('share_with_friend')->after('accept_friend_request');
            $table->boolean('email_notification')->after('share_with_friend');
        });
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
