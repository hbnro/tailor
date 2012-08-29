<?php

$expect = phpversion();
$tpl = '<?php echo phpversion();';

$view = Tailor\Base::parse('php', $tpl);
$test = trim($view);

echo "\nINLINE-TEST: ";
echo $expect === $test ? 'OK' : 'FAIL';



$expect = '<p>It works!</p>';

$view = Tailor\Base::partial('index.html');
$test = trim($view);

echo "\nPARTIAL-TEST: ";
echo $expect === $test ? 'OK' : 'FAIL';

echo "\n";
