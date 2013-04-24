<?php

$clear = function ($str) {
  return trim(preg_replace('/\/\*(.+?)\*\//', '', $str));
};

$tpl = '

.foo() {
  candy: "bar";
  @font: asset-path("dummy.ttf");
  value: url(\'@{font}?x#y\');
  width: image-width("php.png");
  background: asset-url("php.png");
  @data: asset-data-uri("px.gif");
  other: url("@{data}");
}

.x { .foo; }

';

$expect = '.x {
  candy: "bar";
  value: url(\'/static/font/dummy.ttf?x#y\');
  width: 32px;
  background: url("/static/img/php.png");
  other: url("data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7");
}';


$view = Tailor\Base::parse('less', $tpl);
$test = $clear($view);

echo "\nINLINE-TEST: ";
echo $expect === $test ? 'OK' : 'FAIL';




$expect = '.x:after {
  content: "' . (float) phpversion() . '";
}';

$view = Tailor\Base::partial('../styles/styless.css');
$test = $clear(Tailor\Base::render($view));

echo "\nPARTIAL-TEST: ";
echo $expect === $test ? 'OK' : 'FAIL';

echo "\n";
