<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Media;

class AddAdvancedFieldsToMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('media', function (Blueprint $table) {
            $table->tinyInteger('source')->nullable()->after('user_id');
            $table->tinyInteger('user_expertise')->nullable()->after('source');
            $table->string('target_audience')->nullable()->after('user_expertise');
            $table->integer('state_id')->nullable()->unsigned()->after('target_audience');
            $table->string('city')->nullable()->after('state_id');
            $table->string('area')->nullable()->after('city');
            $table->integer('language_id')->nullable()->unsigned()->after('area');
            $table->tinyInteger('caption_available')->default(Media::CAPTION_UNAVAILABLE)->after('language_id');
            
            $table->foreign('state_id')
                    ->references('id')
                    ->on('states')
                    ->onUpdate('cascade')
                    ->onDelete('SET NULL');
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
            $table->dropForeign(['state_id']);
            $table->dropColumn([
                'caption_available',
                'language_id',
                'area',
                'city',
                'state_id',
                'target_audience',
                'user_expertise',
                'source'
            ]);
        });
    }
}
