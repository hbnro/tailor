<?php

$expect = '<a href="#">Link</a>';
$tpl = '%a{ :href => "#" } Link';

$view = Tailor\Base::parse('haml', $tpl);
$test = trim($view);

echo "\nINLINE-TEST: ";
echo $expect === $test ? 'OK' : 'FAIL';



$expect = '<!DOCTYPE html>
<html>
  <head>
    <title>' . phpversion() . '</title>
  </head>
  <body>
    <h1>OK</h1>
    <div id="container">
              <p>bar</p>
          </div>
  </body>
</html>';

$view = Tailor\Base::partial('example.html');
$test = trim($view);

echo "\nPARTIAL-TEST: ";
echo $expect === $test ? 'OK' : 'FAIL';

echo "\n";
