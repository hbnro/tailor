<?php

$expect = '<ul>
  <li>Item n1</li>
  <li>Item n2</li>
  <li>Item n3</li>
</ul>';
$tpl = '<ul>
{% for i in 1..3 %}
  <li>Item n{{ loop.index }}</li>
{% endfor %}
</ul>';

$view = Tailor\Base::parse('twig', $tpl);
$test = @eval('?' . ">$view");
$test = $test(array());

echo "\nINLINE-TEST: ";
echo $expect === $test ? 'OK' : 'FAIL';

$expect = '<span>
  This is xD!!
</span>
';

$view = Tailor\Base::partial('example4.php');
$test = Tailor\Base::render($view, array('foo' => array('bar' => 'xD')));

echo "\nPARTIAL-TEST: ";
echo $expect === $test ? 'OK' : 'FAIL';

echo "\n";
