<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\CollectionDetail;
use App\Models\User;
use App\Models\Media;

class AddForeignKeysToCollectionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        CollectionDetail::whereRaw('collection_id not in (select id from collections)')->delete();
        CollectionDetail::whereRaw('media_id not in (select id from media)')->delete();
        Schema::table('collection_details', function (Blueprint $table) {
            $table->foreign('collection_id')
                    ->references('id')
                    ->on('collections')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            $table->foreign('media_id')
                    ->references('id')
                    ->on('media')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
        });
        
        $folders = explode('/', Media::FOLDER);
        $parentFolder = '';
        foreach($folders as $folder) {
            if(!File::exists($parentFolder . $folder)) {
                File::makeDirectory($parentFolder . $folder);
            }
            $parentFolder .= $folder.'/';
        }
        foreach(User::all() as $user) {
            if(!File::exists(Media::FOLDER . User::FOLDER_PREFIX . $user->id)) {
                File::makeDirectory(Media::FOLDER . User::FOLDER_PREFIX . $user->id);
            }
            chmod(Media::FOLDER . User::FOLDER_PREFIX . $user->id, 0777);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('collection_details', function (Blueprint $table) {
            $table->dropForeign('collection_details_media_id_foreign');
            $table->dropForeign('collection_details_collection_id_foreign');
        });
    }
}
