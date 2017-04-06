<?php

namespace App\Library;

use App;

class S3 {
  static public function putObject ($key, $sourceFile) {
    $s3 = App::make('aws')->createClient('s3');
    return $s3->putObject(array(
      'Bucket'      => config('app.resources_name'),
      'Key'         => $key,
      'SourceFile'  => $sourceFile,
      'ACL'         => 'public-read'
    ));
  }

  static public function deleteObject ($key) {
    $s3 = App::make('aws')->createClient('s3');
    return $s3->deleteObject(array(
      'Bucket' => config('app.resources_name'),
      'Key'    => $key,
    ));
  }

  static public function deleteObjects ($objects) {
    $s3 = App::make('aws')->createClient('s3');
    return $s3->deleteObjects([
      'Bucket' => config('app.resources_name'),
      'Delete' => [
        'Objects' => array_map(function ($o) { return [ 'Key' => $o ]; }, $objects),
        'Quiet' => true
      ]
    ]);
  }

  static public function listObjects ($path) {
    $s3 = App::make('aws')->createClient('s3');
    return $s3->listObjects([
      'Bucket' => config('app.resources_name'),
      'Prefix' => $path
    ]);
  }
}
