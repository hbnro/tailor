<?php

namespace Tailor;

class Config
{

  private static $bag = array(
                    // cache
                    'cache_dir' => './tmp',
                    // files
                    'views_dir' => './views',
                    'images_dir' => './images',
                    'styles_dir' => './styles',
                    'scripts_dir' => './scripts',
                    // urls
                    'images_url' => '/img',
                    'styles_url' => '/css',
                    'scripts_url' => '/js',
                  );



  public static function set($key, $value = NULL)
  {
    static::$bag[$key] = $value;
  }

  public static function get($key, $default = FALSE)
  {
    return isset(static::$bag[$key]) ? static::$bag[$key] : $default;
  }

}
