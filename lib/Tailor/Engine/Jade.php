<?php

namespace Tailor\Engine;

class Jade
{

  private $source = '';
  private $filename = FALSE;

  public static $exts = array('jade');

  public function __construct($source, $filename = FALSE)
  {
    $this->source   = $source;
    $this->filename = $filename;
  }

  public function render()
  {
    $jade = new \Jade\Jade(array(
      'prettyprint' => TRUE
    ));

    return $jade->compile($this->source);
  }

}
