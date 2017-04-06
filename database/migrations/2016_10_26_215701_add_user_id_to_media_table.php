<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Media;
use Illuminate\Support\Facades\DB;

class AddUserIdToMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('media', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->after('type');
        });
        
        Media::whereRaw('screen_name not in (select screen_name from users)')->delete();
        Media::whereRaw('email not in (select email from users)')->delete();
        
        DB::update('UPDATE'.
                   '     media m'.
                   '     JOIN users u ON m.screen_name = u.screen_name'.
                   ' SET'.
                   '     m.user_id = u.id');
        
        Schema::table('media', function (Blueprint $table) {
            $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
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
        Schema::table('media', function (Blueprint $table) {
            $table->dropForeign('media_user_id_foreign');
            $table->dropColumn('user_id');
        });
    }
}
