<?php

$clear = function ($str) {
  return trim(preg_replace('/\/\/(.+?)$/m', '', $str));
};

$expect = 'var foo;

foo = function() {
  return candy("bar");
};';
$tpl = 'foo = ->
  candy "bar"';

$view = Tailor\Base::parse('coffee', $tpl);
$test = $clear($view);

echo "\nINLINE-TEST: ";
echo $expect === $test ? 'OK' : 'FAIL';



$expect = '(function($) {
  return $(function() {
    return console.log("' . (float) phpversion() . '");
  });
})(jQuery);';

$view = Tailor\Base::partial('../scripts/script.js');
$test = $clear(Tailor\Base::render($view));

echo "\nPARTIAL-TEST: ";
echo $expect === $test ? 'OK' : 'FAIL';

echo "\n";
