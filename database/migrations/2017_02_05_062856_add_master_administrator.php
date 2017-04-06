<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Role;
use App\Models\User;

class AddMasterAdministrator extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      $masterAdministrator = Role::masterAdministrator()->first();
      $userDat = User::where('screen_name', 'Dat7MD')->first();
      if (!is_null($userDat)) {
        if (!$userDat->hasRole(Role::MASTER_ADMINISTRATOR)) {
          $userDat->attachRole($masterAdministrator);
        }
      }
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
