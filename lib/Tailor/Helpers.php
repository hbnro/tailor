<?php

namespace Tailor;

class Helpers
{

  private static $files = array();
  private static $images = array();



  public static function path($for, $on = 'views_dir') {
    if (empty(static::$files["$on#$for"])) {
      if ( ! is_file($tmp = static::resolve($for, $on))) {
        return; // TODO: raise exception
      }
      static::$files["$on#$for"] = $tmp;
    }
    return static::$files["$on#$for"];
  }

  public static function image($path) {
    if ($test = static::path($path, 'images_dir')) {
      if (empty(static::$images[$path])) {
        static::$images[$path] = array(
          'dims' => getimagesize($test),
          'size' => filesize($test),
          'file' => $test,
        );
      }
      return static::$images[$path];
    }

    // TODO: raise exception
  }

  public static function resolve($path, $on = 'views_dir') {
    $root = Config::get($on);

    $path = str_replace(array('\\', '/'), DIRECTORY_SEPARATOR, $path);
    $path = preg_replace('/^\.' . preg_quote(DIRECTORY_SEPARATOR, '/') . '/', $root, $path);

    while (substr($path, 0, 3) === '..'.DIRECTORY_SEPARATOR) {
      $path = substr($path, 2);
      $root = dirname($root);
    }

    $path = $root.DIRECTORY_SEPARATOR.ltrim($path, DIRECTORY_SEPARATOR);
    $path = static::findfile("$path*", 0);

    return $path;
  }

  public static function findfile($path, $index = -1) {
    $set = glob($path, GLOB_MARK | GLOB_BRACE);

    if ($index <> -1) {
      return isset($set[$index]) ? $set[$index] : FALSE;
    }
    return $set;
  }

  public static function functions() {
    static $map = NULL;


    if ( ! $map) {
      $reduce_str = function ($val, $re) {
          $out = array();

          if (is_array($val)) {
            foreach ($val as $key => $sub) {
              if (is_array($sub)) {
                @list($type, $delim, $value) = $sub;
                ($type == 'string') && $out []= $re($value, $re);
              } else {
                $out []= $sub;
              }
            }
          }

          return join('', $out);
        };

      $image_mapper = function ($key, $reduce) {
          return function ($xarg) use ($key, $reduce) {
              @list($type, $delim, $string) = $xarg;

              if ($type == 'string') {
                $test = \Tailor\Helpers::image($reduce($string, $reduce));
                return array('number', $test['dims'][$key], 'px');
              }
            };
        };

      $normalize_args = function ($array) {
          static $types = array('string', 'keyword', 'number', 'list');

          if ( ! empty($array[0])) {
            if (in_array($array[0], $types)) {
              return array($array);
            }
          }
          return $array;
        };


      // TODO: other assets support?

      $image_url = function ($xarg)
        use ($reduce_str) {
          @list($type, $delim, $string) = $xarg;

          if ($type == 'string') {
            $file = \Tailor\Helpers::path($reduce_str($string, $reduce_str), 'images_dir');
            $file = strtr(str_replace(\Tailor\Config::get('images_dir'), \Tailor\Config::get('images_url'), $file), '\\', '/');

            return "url({$delim}$file{$delim})";
          }
        };

      $image_width  = $image_mapper(0, $reduce_str);
      $image_height = $image_mapper(1, $reduce_str);


      foreach (array('image_url', 'image_width', 'image_height') as $fn) {
        $helper   = $$fn;
        $callback = function ($xarg)
          use ($helper, $normalize_args) {
            return call_user_func_array($helper, $normalize_args($xarg));
          };

        $map[strtr($fn, '_', '-')] = $callback;
      }
    }

    return $map;
  }

}
