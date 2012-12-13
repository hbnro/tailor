<?php

namespace Tailor;

class Helpers
{

  private static $files = array();
  private static $images = array();

  private static $allowed = array(
                    'fonts_dir' => array('eot', 'woff', 'otf', 'ttf', 'svg'),
                    'images_dir' => array('jpeg', 'jpg', 'png', 'gif'),
                  );

  private static $mime_types = array(
                    'eot' => 'application/vnd.ms-fontobject',
                    'woff' => 'application/x-woff',
                    'otf' => 'application/octet-stream',
                    'ttf' => 'application/octet-stream',
                    'svg' => 'image/svg+xml',
                    'jpeg' => 'image/jpeg',
                    'jpg' => 'image/jpeg',
                    'png' => 'image/png',
                    'gif' => 'image/gif',
                  );



  public static function path($for, $on = 'views_dir')
  {
    if (empty(static::$files["$on#$for"])) {
      if ( ! is_file($tmp = static::resolve($for, $on))) {
        throw new \Exception("The file '$for' does not exists");
      }
      static::$files["$on#$for"] = $tmp;
    }
    return static::$files["$on#$for"];
  }

  public static function image($path)
  {
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

    throw new \Exception("The image '$path' does not exists");
  }

  public static function resolve($path, $on = 'views_dir')
  {
    $root = \Tailor\Config::get($on);

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

  public static function findfile($path, $index = -1)
  {
    $set = glob($path, GLOB_MARK | GLOB_BRACE);

    if ($index <> -1) {
      return isset($set[$index]) ? $set[$index] : FALSE;
    }
    return $set;
  }

  public static function functions()
  {
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


      $path_for = function ($reduce, $wrapper, $allowed) {
          return function ($xarg) use ($reduce, $wrapper, $allowed) {
              @list($type, $delim, $string) = $xarg;

              if ($type == 'string') {
                $tmp = $reduce($string, $reduce);
                $ext = substr($tmp, strrpos($tmp, '.') + 1);

                foreach ($allowed as $path => $set) {
                  if (in_array($ext, $set)) {
                    break;
                  }
                }

                $file = \Tailor\Helpers::path($tmp, $path);
                $test = strtr(str_replace(\Tailor\Config::get($path), \Tailor\Config::get(str_replace('_dir', '_url', $path)), $file), '\\', '/');

                return @sprintf(strtr($wrapper instanceof \Closure ? $wrapper($ext, $path, $file, $delim) : $wrapper, ' ', $delim), $test);
              }
            };
        };

      $fn['asset_url'] = $path_for($reduce_str, 'url( %s )', static::$allowed);
      $fn['asset_path'] = $path_for($reduce_str, '%s', static::$allowed);

      $set = static::$mime_types;
      $fn['asset_data_uri'] = $path_for($reduce_str, function ($ext, $path, $file, $delim)
        use ($set) {
          return sprintf('data:%s;base64,%s', $set[$ext], base64_encode(file_get_contents($file)));
        }, static::$allowed);

      $fn['image_width']  = $image_mapper(0, $reduce_str);
      $fn['image_height'] = $image_mapper(1, $reduce_str);

      foreach ($fn as $name => $helper) {
        $callback = function ($xarg)
          use ($helper, $normalize_args) {
            return call_user_func_array($helper, $normalize_args($xarg));
          };

        $map[strtr($name, '_', '-')] = $callback;
      }
    }

    return $map;
  }

}
