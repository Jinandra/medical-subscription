<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Collection;

class AddOriginalIdToCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('collections', function (Blueprint $table) {
            $table->integer('original_id')->unsigned()->after('description')->nullable();
            $table->foreign('original_id')
                    ->references('id')
                    ->on('collections')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
        });
        Collection::where('is_copy', 1)->delete();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('collections', function (Blueprint $table) {
            $table->dropForeign('collections_original_id_foreign');
            $table->dropColumn('original_id');
        });
    }
}
