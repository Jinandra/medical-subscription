<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\User,
    App\Models\Collection;


class ConnectingCategoryAndCollection extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      if ( !Schema::hasColumn('collections', 'category_id') ) {
        Schema::table('collections', function (Blueprint $table) {
          $table->integer('category_id')->unsigned()->nullable();
        });
      }
      foreach (User::all() as $user) {
        $user->syncMedicalInterestCollections();
      }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      if ( Schema::hasColumn('collections', 'category_id') ) {
        Collection::whereNotNull('category_id')->delete();
        Schema::table('collections', function (Blueprint $table) {
          $table->dropColumn('category_id');
        });
      }
    }
}
