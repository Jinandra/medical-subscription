<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRowUserAdministrator extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('users')->insert(
            array(
                'id'            => 1,
                'name'          => 'Enfolink Administrator',                
                'screen_name'   => 'enfolink_admin',                
                'email'         => 'admin@enfolink.com',                
                'password'      => Hash::make('123456'),
                'user_status'   => 'active'
                
            )
        );

        DB::table('role_user')->insert(
            array(                
                'user_id'       => 1,                
                'role_id'       => 1                
                
            )
        );
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
