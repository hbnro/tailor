<?php

require dirname(__DIR__).DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

$base_dir  = __DIR__.DIRECTORY_SEPARATOR.'assets';
$cache_dir = $base_dir.DIRECTORY_SEPARATOR.'cache';

Tailor\Config::set('cache_dir', $cache_dir);

is_dir($cache_dir) OR mkdir($cache_dir);
$files = glob("$cache_dir/*");
array_map('unlink', $files);


Tailor\Config::set('views_dir', $base_dir.DIRECTORY_SEPARATOR.'views');
Tailor\Config::set('images_dir', $base_dir.DIRECTORY_SEPARATOR.'images');
Tailor\Config::set('styles_dir', $base_dir.DIRECTORY_SEPARATOR.'styles');
Tailor\Config::set('scripts_dir', $base_dir.DIRECTORY_SEPARATOR.'scripts');

Tailor\Config::set('images_url', '/static/img');
Tailor\Config::set('styles_url', '/static/css');
Tailor\Config::set('scripts_url', '/static/js');



foreach (Tailor\Base::$templates as $type => $class) {
  $parts = explode('\\', $class);
  $exts  = $class::$exts;
  $klass = end($parts);

  echo "\n== $klass";
  echo "\nEXTS: " . join(', ', $exts);

  Tailor\Base::register($exts, $class);

  require __DIR__.DIRECTORY_SEPARATOR.'engines'.DIRECTORY_SEPARATOR."$type.php";
}
