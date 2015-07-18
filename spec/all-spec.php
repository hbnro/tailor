<?php

$base_dir  = __DIR__.DIRECTORY_SEPARATOR.'fixtures';
$cache_dir = $base_dir.DIRECTORY_SEPARATOR.'.cache';

\Tailor\Config::set('cache_dir', $cache_dir);

is_dir($cache_dir) OR mkdir($cache_dir);
$files = glob("$cache_dir/*");
array_map('unlink', $files);

\Tailor\Config::set('views_dir', $base_dir.DIRECTORY_SEPARATOR.'views');
\Tailor\Config::set('fonts_dir', $base_dir.DIRECTORY_SEPARATOR.'fonts');
\Tailor\Config::set('images_dir', $base_dir.DIRECTORY_SEPARATOR.'images');
\Tailor\Config::set('styles_dir', $base_dir.DIRECTORY_SEPARATOR.'styles');
\Tailor\Config::set('scripts_dir', $base_dir.DIRECTORY_SEPARATOR.'scripts');

\Tailor\Config::set('fonts_url', '/static/font');
\Tailor\Config::set('images_url', '/static/img');
\Tailor\Config::set('styles_url', '/static/css');
\Tailor\Config::set('scripts_url', '/static/js');

\Tailor\Base::initialize();

function load($engine, $code) {
  return \Tailor\Base::parse($engine, $code);
}

function render($view, $locals = array()) {
  return Tailor\Base::render($view, $locals);
}

function partial($path) {
  return \Tailor\Base::partial($path);
}

describe('Engines', function() {
  foreach (glob(__DIR__.'/engines/*.php') as $test_file) {
    require $test_file;
  }
});
