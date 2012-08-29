<?php

namespace Tailor\Engine;

class Haml
{

  private $source = '';
  private $filename = FALSE;

  private static $obj = NULL;

  public static $exts = array('haml');



  public function __construct($source, $filename = FALSE) {
    $this->source   = $source;
    $this->filename = $filename;
  }

  public function render() {
    return static::parse($this->source, $this->filename);
  }

  public static function parse($text, $filename = 'unknown') {
    return static::instance()->compileString($text, $filename);
  }



  private static function instance() {
    if (is_null(static::$obj)) {
      static::$obj = new \MtHaml\Environment('php');
    }
    return static::$obj;
  }

}
