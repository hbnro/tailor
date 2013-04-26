<?php

namespace Tailor;

class Base
{

  private static $handlers = array();

  public static $templates = array(
                    'php' => '\\Tailor\\Engine\\Php',
                    'less' => '\\Tailor\\Engine\\Less',
                    'haml' => '\\Tailor\\Engine\\Haml',
                    'scss' => '\\Tailor\\Engine\\Scss',
                    'puke' => '\\Tailor\\Engine\\Puke',
                    'twig' => '\\Tailor\\Engine\\Twig',
                    'neddle' => '\\Tailor\\Engine\\Neddle',
                    'markdown' => '\\Tailor\\Engine\\Markdown',
                    'coffeescript' => '\\Tailor\\Engine\\CoffeeScript',
                  );

  public static function initialize()
  {
    foreach (static::$templates as $type => $class) {
      static::register($class::$exts, $class);
    }
  }

  public static function register($exts, $klass)
  {
    foreach ((array) $exts as $one) {
      static::$handlers[$one] = $klass;
    }
  }

  public static function partial($path, $from = 'views_dir')
  {
    $tmp_file = \Tailor\Helpers::resolve($path, $from);
    $cache_file = \Tailor\Helpers::cached($path);

    if ( ! is_file($tmp_file)) {
      if (is_file($cache_file)) {
        // TODO: better fallback?
        return $cache_file;
      }

      throw new \Exception("Partial '$path' does not exists");
    }

    if (($tmp_file <> $cache_file) && (substr_count($tmp_file, '.') > 1)) {
      if (is_file($cache_file)) {
        if (filemtime($tmp_file) > filemtime($cache_file)) {
          @unlink($cache_file);
        }
      }

      if ( ! is_file($cache_file)) {
        file_put_contents($cache_file, static::compile($tmp_file));
      }

      return $cache_file;
    }

    return $tmp_file;
  }

  public static function compile($view)
  {
    if ( ! is_file($view)) {
      throw new \Exception("The file '$view' does not exists");
    }

    $output = file_get_contents($view);
    $parts  = explode('.', basename($view));

    while ($parts) {
      $type = array_pop($parts);

      if ((sizeof($parts) > 1) && array_key_exists($type, static::$handlers)) {
        $output = static::parse($type, $output, $view);
        continue;
      }
      break;
    }

    return $output;
  }

  public static function render($view, array $vars = array())
  {
    if ( ! is_file($view)) {
      throw new \Exception("The file '$view' does not exists");
    }

    return call_user_func(function () {
      ob_start();
      extract(func_get_arg(1));

      $_ = require func_get_arg(0);

      if ($_ instanceof \Closure) {
        echo $_(get_defined_vars());
      }

      return ob_get_clean();
    }, $view, $vars);
  }

  public static function parse($type, $context, $filename = FALSE)
  {
    if ( ! isset(static::$handlers[$type])) {
      throw new \Exception("Missing handler for '$type' templates");
    }

    $template = static::$handlers[$type];
    $handler  = new $template($context, $filename);
    $view     = new \Tailor\Template($handler);

    return $view->render();
  }

}
