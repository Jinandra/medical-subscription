<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Media;
use File;

class MoveMedia extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media:move';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Move media from project path to aws s3';

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
      echo "Moving media from disk storage to s3...".PHP_EOL;
      foreach (Media::where('file_name', '!=', '')->get() as $media) {
        $path = "{$dir}/user_{$media->user_id}/{$media->file_name}";
        if (is_file($path)) {
          echo "Uploading {$path} to s3/user_{$media->user_id}/{$media->file_name}".PHP_EOL;
          $media->uploadFile($path);
        }
      }
      echo "Done.".PHP_EOL;
    }
}
