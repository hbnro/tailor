<?php

namespace Tailor;

class Template
{

  private $engine = NULL;

  public function __construct($engine)
  {
    $this->engine = $engine;
  }

  public function render()
  {
    try {
      return $this->engine->render();
    } catch (\Exception $e) {
      return $e->getMessage();
    }
  }

}
