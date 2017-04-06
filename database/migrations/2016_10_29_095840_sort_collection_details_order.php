<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Collection;
use App\Models\CollectionDetail;

class SortCollectionDetailsOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      $collections = Collection::all();
      foreach ($collections as $collection) {
        $media  = CollectionDetail::where('collection_id', $collection->id)
                    ->orderBy('sort_order', 'ASC')
                    ->orderBy('id', 'DESC')
                    ->get();
        $sortOrder = 1;
        foreach ($media as $medium) {
          DB::table('collection_details')
            ->where('id', $medium->id)
            ->update(['sort_order' => $sortOrder]);
          $sortOrder += 1;
        }
      }
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
