<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Media;
use File;

class CleanupMediaWithoutFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Delete media that has no file on local storage (WARNING: ONLY DO THIS WHEN MIGRATING FROM LOCAL STORAGE TO S3)";

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
      $dir = 'uploads/media';
      echo "Deleting media...".PHP_EOL;
      foreach (Media::where('file_name', '!=', '')->get() as $media) {
        $path = "{$dir}/user_{$media->user_id}/{$media->file_name}";
        if ( !is_file($path) ) {
          echo "Deleting media id {$media->id}".PHP_EOL;
          echo "Uncomment the delete method below".PHP_EOL;
          /* $media->delete(); */
        }
      }
      echo "Done.".PHP_EOL;
    }
}
