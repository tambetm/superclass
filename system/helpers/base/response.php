<?php
namespace helpers\base;

use helpers\URL as helpers_URL;

class Response {

  public static function redirect($url) {
    if (strpos($url, 'http:') !== 0) {
      $url = helpers_URL::base_url().$url;
    }
    header('Location: '.$url);
    exit;
  }

}
