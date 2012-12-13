<?php

$expect = '<ahref="#">Link</a>';
$tpl = 'a { href => "#" } Link';

$view = Tailor\Base::parse('neddle', $tpl);
$test = preg_replace('/\s+/', '', eval('ob_start(); ?' . ">$view<" . '?php return ob_get_clean();'));

echo "\nINLINE-TEST: ";
echo $expect === $test ? 'OK' : 'FAIL';



$expect = '<html><head><title>' . phpversion() . '</title></head><body><h1>OK</h1><divid="container"><p>bar</p></div></body></html>';

$view = Tailor\Base::partial('example2.html');
$test = preg_replace('/\s+/', '', Tailor\Base::render($view));

echo "\nPARTIAL-TEST: ";
echo $expect === $test ? 'OK' : 'FAIL';

echo "\n";
