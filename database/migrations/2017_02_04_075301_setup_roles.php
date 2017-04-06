<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Role;
use App\Models\User;

class SetupRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Role::where('name', 'verified')->delete();

      if (is_null(Role::paidUser()->first())) {
        Role::create(['name' => Role::PAID_USER, 'display_name' => 'Paid User', 'description' => 'Paid User']);
      }
      $masterAdministrator = Role::masterAdministrator()->first();
      if (is_null($masterAdministrator)) {
        $masterAdministrator = Role::create(['name' => Role::MASTER_ADMINISTRATOR, 'display_name' => 'Master Administrator', 'description' => 'Master Administrator']);
      }

      $regularUser = Role::regularUser()->first();

      // Normalize users
      $userAdmin = User::where('email', 'admin@enfolink.com')->first();
      if (!is_null($userAdmin)) {
        if (!$userAdmin->hasRole(Role::USER)) {
          $userAdmin->attachRole($regularUser);
        }
        if (!$userAdmin->hasRole(Role::MASTER_ADMINISTRATOR)) {
          $userAdmin->attachRole($masterAdministrator);
        }
      }

      $userGor = User::where('email', 'gor.mkhitaryan88@gmail.com')->first();
      if (!is_null($userGor)) {
        if (!$userGor->hasRole(Role::USER)) {
          $userGor->attachRole($regularUser);
        }
      }

      $userGupta = User::where('email', 'jinandra.gupta@gmail.com')->first();
      if (!is_null($userGupta)) {
        if (!$userGupta->hasRole(Role::ADMINISTRATOR)) {
          $userGupta->attachRole(Role::administrator()->first());
        }
      }

      $userFreddy = User::where('email', 'fkresna@gmail.com')->first();
      if (!is_null($userFreddy)) {
        if (!$userFreddy->hasRole(Role::MASTER_ADMINISTRATOR)) {
          $userFreddy->attachRole($masterAdministrator);
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
