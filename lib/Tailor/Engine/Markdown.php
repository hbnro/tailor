<?php

namespace Tailor\Engine;

class Markdown
{

  private $source = '';
  private $filename = FALSE;

  private static $obj = NULL;

  public static $exts = array('md', 'mkd', 'markdown');



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
    return static::instance()->transformMarkdown($text);
  }



  private static function instance()
  {
    if (static::$obj === NULL) {
      static::$obj = new \dflydev\markdown\MarkdownExtraParser;
    }
    return static::$obj;
  }

}
