<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertRolePermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('roles')->insert(
            array(
                'id'            => 1,
                'name'          => 'administrator',
                'display_name'  => 'Administrator',
                'description'   => 'Web Administrator',
                
            )
        );

        DB::table('roles')->insert(
            array(
                'id'            => 2,
                'name'          => 'user',
                'display_name'  => 'User',
                'description'   => 'Web User',
                
            )
        );

        DB::table('roles')->insert(
            array(
                'id'            => 3,
                'name'          => 'verified',
                'display_name'  => 'Verified',
                'description'   => 'Verified User',
                
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
