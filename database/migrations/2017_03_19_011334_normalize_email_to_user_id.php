<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\User;
use App\Models\LikeDislike;
use App\Models\History;
use App\Models\Favorite;
use App\Models\Ucode;
use App\Models\UcodeHistory;
use App\Models\CollectionsHistory;

class NormalizeEmailToUserId extends Migration
{
    public function convert ($tableName, $rows, $cache) {
      if ( !Schema::hasColumn($tableName, 'user_id') ) {
        Schema::table($tableName, function (Blueprint $table) {
          $table->integer('user_id')->unsigned()->after('id')->nullable();
          $table->foreign('user_id')
            ->references('id')
            ->on('users')
            ->onUpdate('cascade')
            ->onDelete('cascade');
        });
      }
      if ( Schema::hasColumn($tableName, 'email') ) {
        Schema::table($tableName, function (Blueprint $table) use ($rows, $cache) {
          $users = User::all();
          foreach ($rows as $r) {
            $email  = $r['email'];
            $userId = null;
            if ( !isset($cache[$email]) ) {
              $index = $users->search(function ($item) use ($email) {
                return $item->email === $email;
              });
              if ($index === false) {
                if ((strtolower($email) === 'guest')) {
                  $userId = null;
                  $cache[$email] = $userId;
                } else {
                  $r->delete();
                }
              } else {
                $userId = $users->get($index)->id;
                $cache[$email] = $userId;
              }
            } else {
              $userId = $cache[$email];
            }
            if ( !is_null($userId) ) {
              $r->user_id = $userId;
              $r->save();
            }
          }
        });
      }
      if ( Schema::hasColumn($tableName, 'email') ) {
        Schema::table($tableName, function (Blueprint $table) {
          $table->dropColumn('email');
        });
      }

      return $cache;
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      echo "Normalizing database, please wait...\n";
      $cache = [];
      $cache = $this->convert((new LikeDislike)->getTable(), LikeDislike::all(), $cache);
      $cache = $this->convert((new Favorite)->getTable(), Favorite::all(), $cache);
      $cache = $this->convert((new Ucode)->getTable(), Ucode::all(), $cache);
      $cache = $this->convert((new History)->getTable(), History::all(), $cache);
      $cache = $this->convert((new UcodeHistory)->getTable(), UcodeHistory::all(), $cache);
      $cache = $this->convert((new CollectionsHistory)->getTable(), CollectionsHistory::all(), $cache);
      echo "Normalizing database done.\n";
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
