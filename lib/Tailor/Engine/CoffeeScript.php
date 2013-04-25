<?php

namespace Tailor\Engine;

class CoffeeScript
{

  private $source = '';
  private $filename = FALSE;

  public static $exts = array('coffee');

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
    $old = error_reporting();
    error_reporting(0);

    try {
      $out = \CoffeeScript\Compiler::compile($text, array('bare' => TRUE));
    } catch (\Exception $e) {}

    error_reporting($old);

    if (isset($e)) {
      return $e->getMessage();
    }

    return $out;
  }

}
