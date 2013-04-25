<?php

namespace Tailor\Engine;

class Twig
{

  private $source = '';
  private $filename = FALSE;

  private static $obj = NULL;

  public static $exts = array('twig', 'twg');

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
    $text = static::instance()->compileSource($text, $text);

    preg_match('/\bclass\s+(__TwigTemplate_\S+)/', $text, $match);

    $text .= "\$$match[1] = new $match[1](\\Tailor\\Engine\\Twig::instance());\n";
    $text .= "return function (\$_) use (\$$match[1]) { return \$$match[1]->render(\$_); };\n";

    return $text;
  }

  public static function instance()
  {
    if (static::$obj === NULL) {
      $loader = new \Twig_Loader_String();
      static::$obj = new \Twig_Environment($loader);
    }

    return static::$obj;
  }

}
