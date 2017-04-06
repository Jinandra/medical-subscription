<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Media;

class RegenerateThumbnails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'thumbnails:regenerate {media?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Regenerate users thumbnail to aws s3';

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
      $mediaId = $this->argument('media');
      if ( is_null($mediaId) ) {
        echo "Generating thumbnails for all media...".PHP_EOL;
        foreach (Media::all() as $media) {
          echo "Generating for media id {$media->id}".PHP_EOL;
          $media->generateThumbnail();
        }
      } else {
        echo "Generating thumbnail for media id {$mediaId}...".PHP_EOL;
        Media::find($mediaId)->generateThumbnail();
      }
      echo "Done.".PHP_EOL;
    }
}
