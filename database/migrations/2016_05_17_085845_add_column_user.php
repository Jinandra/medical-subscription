<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function ($table) {
            $table->string('first_name',255)->after('password');
            $table->string('last_name',255)->after('first_name');
            $table->string('screen_name',100)->after('last_name');
            $table->mediumText('address')->after('screen_name');
            $table->integer('user_rating',false)->after('address');
            $table->string('user_role',50)->after('user_rating');
            $table->boolean('is_verified',1)->after('user_role');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function ($table) {
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
            $table->dropColumn('screen_name');
            $table->dropColumn('address');
            $table->dropColumn('user_rating');
            $table->dropColumn('user_role');
            $table->dropColumn('is_verified');
        });
    }
}
