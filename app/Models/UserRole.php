<?php 
namespace App\Models;

use Zizaco\Entrust\EntrustRole;
use Illuminate\Database\Eloquent\Model;
use DB,
    Auth;

class UserRole extends Model
{
    protected $table = 'role_user';

}
