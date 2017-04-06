<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\DetailUcode;
use App\Models\EmailHistory;
use App\Models\UcodeHistory;
use Illuminate\Support\Facades\DB;

class AddUcodeIdToSomeTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detail_ucode', function (Blueprint $table) {
            $table->integer('ucode_id')->unsigned()->after('ucode');
        });
        DetailUcode::whereRaw('ucode not in (select ucode from ucode)')->delete();
        DB::update('UPDATE'.
                   '     detail_ucode du'.
                   '     JOIN ucode u ON du.ucode = u.ucode'.
                   ' SET'.
                   '     du.ucode_id = u.id');
        Schema::table('detail_ucode', function (Blueprint $table) {
            $table->foreign('ucode_id')
                    ->references('id')
                    ->on('ucode')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
        });
        
        Schema::table('email_history', function (Blueprint $table) {
            $table->integer('ucode_id')->unsigned()->nullable()->after('ucode');
            $table->integer('user_id')->unsigned()->change();
        });
        DB::update('UPDATE'.
                   '     email_history eh'.
                   '     JOIN ucode u ON eh.ucode = u.ucode'.
                   ' SET'.
                   '     eh.ucode_id = u.id');
        Schema::table('email_history', function (Blueprint $table) {
            $table->foreign('ucode_id')
                    ->references('id')
                    ->on('ucode')
                    ->onUpdate('cascade')
                    ->onDelete('SET NULL');
        });
        
        Schema::table('ucode_history', function (Blueprint $table) {
            $table->integer('ucode_id')->unsigned()->after('ucode');
        });
        UcodeHistory::whereRaw('ucode not in (select ucode from ucode)')->delete();
        DB::update('UPDATE'.
                   '     ucode_history uh'.
                   '     JOIN ucode u ON uh.ucode = u.ucode'.
                   ' SET'.
                   '     uh.ucode_id = u.id');
        Schema::table('ucode_history', function (Blueprint $table) {
            $table->foreign('ucode_id')
                    ->references('id')
                    ->on('ucode')
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
        Schema::table('ucode_history', function (Blueprint $table) {
            $table->dropForeign('ucode_history_ucode_id_foreign');
            $table->dropColumn('ucode_id');
        });
        
        Schema::table('email_history', function (Blueprint $table) {
            $table->dropForeign('email_history_ucode_id_foreign');
            $table->dropColumn('ucode_id');
        });
        
        Schema::table('detail_ucode', function (Blueprint $table) {
            $table->dropForeign('detail_ucode_ucode_id_foreign');
            $table->dropColumn('ucode_id');
        });
    }
}
