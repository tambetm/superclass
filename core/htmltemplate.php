<?php

class core_HTMLTemplate {

  static private $level = 0;

  protected $self_closing_tags = array(
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

  protected $block_level_elements = array(
    'address' => 1,
    'article' => 1,
    'aside' => 1,
    'audio' => 1,
    'blockquote' => 1,
    'body' => 1,
    'canvas' => 1,
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
    if (strpos($name, '_') !== 0) {
      throw new BadMethodCallException("Invalid method '$name'");
    }

    // call the same method without leading underscore
    $method = substr($name, 1);

    // if third argument is true, then output tag only if it has contents
    if (isset($arguments[2]) && $arguments[2] === true) {
      ob_start();
      self::$level++;
      call_user_func(array($this, $method));
      self::$level--;
      $content = ob_get_clean();
      if (!$content) return;
    }

    // first non-array argument is tag name.
    if (isset($arguments[0]) && !is_null($arguments[0]) && !is_array($arguments[0])) {
      $tag = $arguments[0];
    } else if (isset($arguments[1]) && !is_null($arguments[1]) && !is_array($arguments[1])) {
      $tag = $arguments[1];
    } else {
      // if missing, use the last part of method name.
      $tags = explode('_', $name);
      $tag = end($tags);
    }

    // first array argument is list of attributes. NB! null is not an array!
    if (isset($arguments[0]) && is_array($arguments[0])) {
      $attributes = $arguments[0];
    } else if (isset($arguments[1]) && is_array($arguments[1])) {
      $attributes = $arguments[1];
    }

    // concatenate attributes into string.
    $attrs = '';
    if (isset($attributes)) {
      foreach ($attributes as $name => $value) {
        // escape attribute values for convenience
        $attrs .= ' '.$name.'="'.htmlspecialchars($value).'"';
      }
    }

    $this->indent(self::$level);

    // output tag and it's contents
    if (isset($content)) {
      echo "<$tag$attrs>$content</$tag>";
    } else if (method_exists($this, $method)){
      echo "<$tag$attrs>";
      self::$level++;
      call_user_func(array($this, $method));
      self::$level--;
      if (isset($this->block_level_elements[$tag])) {
        $this->indent(self::$level);
      }
      echo "</$tag>";
    } else if (isset($this->self_closing_tags[$tag])){
      echo "<$tag$attrs/>";
    } else {
      echo "<$tag$attrs></$tag>";
    }
  }

  protected function indent($level) {
    echo "\n".str_repeat(' ', $level * 2);
  }
}
