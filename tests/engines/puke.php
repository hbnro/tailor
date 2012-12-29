<?php

$expect = '<ahref="#">Link</a>';
$tpl = 'a(array("href" => "#"), "Link");';

$view = Tailor\Base::parse('puke', $tpl);
$test = preg_replace('/\s+/', '', @call_user_func(@eval('?' . ">$view")));

echo "\nINLINE-TEST: ";
echo $expect === $test ? 'OK' : 'FAIL';


$expect = '<html><head><title>' . phpversion() . '</title></head><body><h1>OK</h1><divid="container"><p>bar</p></div></body></html>';

$view = Tailor\Base::partial('example3.html');
$test = preg_replace('/\s+/', '', Tailor\Base::render($view));

echo "\nPARTIAL-TEST: ";
echo $expect === $test ? 'OK' : 'FAIL';

echo "\n";
