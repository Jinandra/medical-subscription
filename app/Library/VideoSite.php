<?php

namespace App\Library;

use App;

class VideoSite {
  static public function extractYoutubeId ($url) {
    if (strpos($url, "youtu.be") > 0) {
      $temp = explode("/", $url);
      if (isset($temp[3])) {
        return $temp[3];
      }
    } else if (strpos($url, "youtube.com") > 0) {
      $a = strpos($url, "v=");
      $b = strpos($url, "&");
      if ($b > 0) {
        return substr($url, $a + 2, $b - $a - 2);
      } else {
        return substr($url, $a + 2);
      }
    }
    return null;
  }

  static public function isYoutube ($url) {
    return strpos($url, "youtu.be") > 0 || strpos($url, "youtube.com") > 0;
  }

  static public function isGoogleDrive ($url) {
    return strpos($url, "drive.google") > 0;
  }

  static public function getDriveEmbed ($url) {
    $matches = array();
    preg_match('/.*.google.com\/(.*?)\/(.*?)\/(.*?)\//', $url, $matches);
    if (count($matches) > 0) {
      return preg_replace('{/$}', '', $matches[0])."/preview";
    }
    return null;
  }

  static public function getYoutubeEmbed ($url) {
    $youtubeId = self::extractYoutubeId($url);
    return is_null($youtubeId) ? null : "https://www.youtube.com/embed/{$youtubeId}?showinfo=0";
  }

  static public function youtubeThumbnailUrl ($url) {
    return "http://img.youtube.com/vi/".self::extractYoutubeId($url)."/default.jpg";
  }
}
