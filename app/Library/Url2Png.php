<?php
namespace App\Library;

use App;

class Url2Png {
	function url2png_v6($url, $args) {

		$URL2PNG_APIKEY = "PAD695CCD70FC3F";
		$URL2PNG_SECRET = "S_D74662BD72F26";

		# urlencode request target
		$options['url'] = urlencode($url);

		$options += $args;

		# create the query string based on the options
		foreach($options as $key => $value) { $_parts[] = "$key=$value"; }

		# create a token from the ENTIRE query string
		$query_string = implode("&", $_parts);
		$TOKEN = md5($query_string . $URL2PNG_SECRET);

		return "https://api.url2png.com/v6/$URL2PNG_APIKEY/$TOKEN/png/?$query_string";

	}
}
/*
$url2png = new Url2Png();
$options['force']     = 'false';      # [false,always,timestamp] Default: false
$options['fullpage']  = 'false';      # [true,false] Default: false
$options['thumbnail_max_width'] = 'false';      # scaled image width in pixels; Default no-scaling.
$options['viewport']  = "1280x1024";  # Max 5000x5000; Default 1280x1024
$src = $url2png->url2png_v6("google.com", $options);
echo $src;

        //$file = $src
        //$file->move('tmp',$file->getClientOriginalName());
        $s3 = App::make('aws')->createClient('s3');
        $s3->putObject(array(
          'Bucket'     => 'enfolinkresources',
      //    'Key'        => Auth::user()->screen_name.'/'.$file->getClientOriginalName(),
          'Key'					=> 'freddy_test/image.png',
					'SourceFile' => $src,
        ));
//        $media->file_name = $file->getClientOriginalName();
//        unlink('tmp/'.$file->getClientOriginalName());
*/
