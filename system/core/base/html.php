<?php
namespace core\base;

use helpers\String;

class HTML {

  static private $level = 0;

  static private $self_closing_tags = array(
    'area' => 1,
    'base' => 1,
    //'basefont' => 1,
    //'bgsound' => 1,
    'br' => 1,
    'col' => 1,
    'command' => 1,
    'embed' => 1,
    //'frame' => 1,
    'hr' => 1,
    'img' => 1,
    'input' => 1,
    //'isindex' => 1,
    'keygen' => 1,
    'link' => 1,
    'meta' => 1,
    'param' => 1,
    'source' => 1,
    'track' => 1,
    'wbr' => 1,
  );

  static private $block_level_elements = array(
    'address' => 1,
    'article' => 1,
    'aside' => 1,
    'audio' => 1,
    'blockquote' => 1,
    'body' => 1,
    'canvas' => 1,
    'colgroup' => 1,
    'dd' => 1,
    'div' => 1,
    'dl' => 1,
    'fieldset' => 1,
    'figcaption' => 1,
    'figure' => 1,
    'figcaption' => 1,
    'footer' => 1,
    'form' => 1,
    'h1' => 1,
    'h2' => 1,
    'h3' => 1,
    'h4' => 1,
    'h5' => 1,
    'h6' => 1,
    'head' => 1,
    'header' => 1,
    'hgroup' => 1,
    'hr' => 1,
    'html' => 1,
    'noscript' => 1,
    'ol' => 1,
    'output' => 1,
    'p' => 1,
    'pre' => 1,
    'section' => 1,
    'table' => 1,
    'tbody' => 1,
    'tfoot' => 1,
    'thead' => 1,
    'tr' => 1,
    'ul' => 1,
    'video' => 1,
  );

  public function __call($name, $arguments) {
    // all internal methods must start with _
    if (!String::starts_with($name, '_')) {
      throw new \BadMethodCallException("Invalid method '$name'");
    }

    // call the same method without leading underscore
    $method = substr($name, 1);

    // if first argument is not an array, then it's tag name.
    if (isset($arguments[0]) && !is_array($arguments[0])) {
      // remove tag name from arguments array and renumber array
      $tag = array_shift($arguments);
    } else {
      // if missing, use the last part of method name.
      $tags = explode('_', $name);
      $tag = end($tags);
    }

    // concatenate attributes into string.
    $attributes = '';
    if (isset($arguments[0]) && is_array($arguments[0])) {
      foreach ($arguments[0] as $name => $value) {
        // escape attribute values for convenience
        $attributes .= ' '.$name.'="'.self::escape($value).'"';
      }
    }

    if (isset($arguments[1])) {
      if (is_string($arguments[1])) {
        // if third argument is string, then it's contents of the tag
        // contents are automatically escaped
        $content = self::escape($arguments[1]);
      } else {
        // if not string, then output tag only if it has contents
        ob_start();
        self::$level++;
        call_user_func(array($this, $method));
        self::$level--;
        $content = ob_get_clean();
        if (!$content) return;
      }
    }

    $this->indent(self::$level);

    // output tag and it's contents
    if (isset($content)) {
      echo "<$tag$attributes>$content</$tag>";
    } else if (method_exists($this, $method)){
      echo "<$tag$attributes>";
      self::$level++;
      call_user_func(array($this, $method));
      self::$level--;
      if (isset(self::$block_level_elements[$tag])) {
        $this->indent(self::$level);
      }
      echo "</$tag>";
    } else if (isset(self::$self_closing_tags[$tag])){
      echo "<$tag$attributes/>";
    } else {
      echo "<$tag$attributes></$tag>";
    }
  }

  protected function indent($level) {
    echo "\n".str_repeat(' ', $level * 2);
  }

  static public function escape($value) {
    return htmlspecialchars($value);
  }

  static public function merge_attributes($attrs1, $attrs2) {
    // handle empty arguments first
    if (!is_array($attrs1) || empty($attrs1)) return $attrs2;
    if (!is_array($attrs2) || empty($attrs2)) return $attrs1;

    foreach ($attrs2 as $name => $value) {
      if (is_null($value)) {
        // null in $attrs2 has special meaning - remove that attribute
        unset($attrs1[$name]);
      } elseif (isset($attrs1[$name]) && $name == 'class') {
        // only class attributes are concatenated
        $attrs1[$name] .= ' '.$value;
      } else {
        // everything else in $attrs2 overwrites $attrs1
        $attrs1[$name] = $value;
      }
    }
    return $attrs1;
  }

}
