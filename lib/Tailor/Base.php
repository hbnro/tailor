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
    if ( ! is_file($dir = \Tailor\Helpers::resolve($path, $from))) {
      throw new \Exception("Partial '$path' does not exists");
    }

    if (substr_count($dir, '.') > 1) {
      $cache_dir  = \Tailor\Config::get('cache_dir');
      $cache_file = $cache_dir.DIRECTORY_SEPARATOR.strtr($path, '\\/', '__');

      if (is_file($cache_file)) {
        if (filemtime($dir) > filemtime($cache_file)) {
          @unlink($cache_file);
        }
      }

      if ( ! is_file($cache_file)) {
        file_put_contents($cache_file, static::compile($dir));
      }
      return $cache_file;
    }
    return $dir;
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
      require func_get_arg(0);
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
