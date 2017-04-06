<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\User;
use App\Models\BundleCart;
use App\Models\Media;

class RenameColumnsBundleCartTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        BundleCart::whereRaw('email not in (select email from users)')->delete();
		BundleCart::whereRaw('id_media not in (select id from media)')->delete();
		
		DB::update('UPDATE'.
				   '	bundle_cart bc'.
				   '	JOIN users u ON u.email = bc.email'.
				   ' SET'.
				   '    bc.email = u.id');		
		
        Schema::table('bundle_cart', function (Blueprint $table) {
			$table->renameColumn('email', 'user_id');
			$table->renameColumn('id_media', 'media_id');
		});
		
		Schema::table('bundle_cart', function (Blueprint $table) {
			$table->integer('user_id')->unsigned()->change();
			$table->integer('media_id')->unsigned()->change();
        });
		Schema::table('bundle_cart', function (Blueprint $table) {
            $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
        });
		
		Schema::table('bundle_cart', function (Blueprint $table) {
            $table->foreign('media_id')
                    ->references('id')
                    ->on('media')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bundle_cart', function (Blueprint $table) {
            $table->dropForeign('bundle_cart_user_id_foreign');
            $table->dropColumn('user_id');
			$table->dropForeign('bundle_cart_media_id_foreign');
            $table->dropColumn('media_id');
        });
    }
}
