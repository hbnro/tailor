<?php

namespace Tailor\Engine;

class Php
{

  private $source = '';
  private $filename = FALSE;

  public static $exts = array('php', 'phtml');



  public function __construct($source, $filename = FALSE) {
    $this->source   = $source;
    $this->filename = $filename;
  }

  public function render() {
    return static::parse($this->source, $this->filename);
  }

  public static function parse($text, $filename = 'unknown') {
    ob_start(); // TODO: this could be better?
    eval('; ?' . ">$text");
    return ob_get_clean();
  }

}
