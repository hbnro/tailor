<?php

namespace Tailor\Engine;

class Puke
{

  private $source = '';
  private $filename = FALSE;

  public static $exts = array('pk', 'puk', 'puke');

  public function __construct($source, $filename = FALSE)
  {
    $this->source   = $source;
    $this->filename = $filename;
  }

  public function render()
  {
    return static::parse($this->source, $this->filename);
  }

  public static function parse($text, $filename = 'unknown')
  {
    if (is_string($text)) {
      $text = preg_replace('/^\s*<\?(php)?\s*/', '', $text);
    }
    $source = \Puke\Base::parse($text);

    return '<' . "?php $source\n";
  }

}
