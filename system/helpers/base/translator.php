<?php
namespace helpers\base;

use helpers\Locale;

class Translator {

  static public function init() {
    include('config/gettext.inc');
    if (!is_array($config)) throw new \UnexpectedValueException("Invalid configuration for gettext");

    $lang = Locale::get_current();
    $charset = Locale::get_charset();

    putenv("LANG=$lang");
    setlocale(LC_MESSAGES, $lang);

    foreach ($config as $domain => $directory) {
      bindtextdomain($domain, $directory);
      bind_textdomain_codeset($domain, $charset);
    }

    // default domain is 'application'
    textdomain('application');
  }

  static public function _() {
    $args = func_get_args();
    $args[0] = gettext($args[0]);
    return call_user_func_array('sprintf', $args);
  }

  static public function _n() {
    $args = func_get_args();
    $args[1] = ngettext($args[0], $args[1], $args[2]);
    array_shift($args);
    return call_user_func_array('sprintf', $args);
  }

  static public function _c() {
    $args = func_get_args();
    $text = $args[0]."\004".$args[1];
    $translation = gettext($text);
    // use translation only when different
    // otherwise we would print context when there is no translation
    if ($translation != $text)
    {
      $args[1] = $translation;
    }
    array_shift($args);
    return call_user_func_array('sprintf', $args);
  }
}

Translator::init($locale); 
