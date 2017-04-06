<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;  
use Zizaco\Entrust\EntrustRole;
use DB;

class Role extends EntrustRole
{
  const ADMINISTRATOR = 'administrator';
  const MASTER_ADMINISTRATOR = 'master-administrator';
  const USER = 'user';
  const PAID_USER = 'paid-user';

  protected $table = 'roles';
  protected $fillable = ['name', 'display_name', 'description'];


  static public function administrator () {
    return self::getRole(self::ADMINISTRATOR);
  }

  static public function regularUser () {
    return self::getRole(self::USER);
  }

  static public function paidUser () {
    return self::getRole(self::PAID_USER);
  }

  static public function masterAdministrator () {
    return self::getRole(self::MASTER_ADMINISTRATOR);
  }

  static public function getRole ($roleName) {
    return self::where('name', $roleName);
  }
}
