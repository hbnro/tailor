<?php

namespace Tailor\Engine;

class Neddle
{

  private $source = '';
  private $filename = FALSE;

  public static $exts = array('nd', 'ndl', 'ned', 'nedl', 'neddle');



  public function __construct($source, $filename = FALSE) {
    $this->source   = $source;
    $this->filename = $filename;
  }

  public function render() {
    return static::parse($this->source, $this->filename);
  }

  public static function parse($text, $filename = 'unknown') {
    return \Neddle\Parser::render($text);
  }

}
