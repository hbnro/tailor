<?php

$clear = function ($str) {
  return trim(preg_replace('/\/\*(.+?)\*\//', '', $str));
};

$tpl = '

.foo() {
  candy: "bar";
  width: image-width("php.png");
  background: image-url("php.png");
}

.x { .foo; }

';

$expect = '.x {
  candy: "bar";
  width: 32px;
  background: url("/static/img/php.png");
}';


$view = Tailor\Base::parse('less', $tpl);
$test = $clear($view);

echo "\nINLINE-TEST: ";
echo $expect === $test ? 'OK' : 'FAIL';




$expect = '.x:after {
  content: "' . phpversion() . '";
}';

$view = Tailor\Base::partial('../styles/styless.css');
$test = $clear($view);

echo "\nPARTIAL-TEST: ";
echo $expect === $test ? 'OK' : 'FAIL';

echo "\n";
