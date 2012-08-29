<?php

$expect = '<p><a href="#">Link</a></p>';
$tpl = '[Link](#)';

$view = Tailor\Base::parse('markdown', $tpl);
$test = trim($view);

echo "\nINLINE-TEST: ";
echo $expect === $test ? 'OK' : 'FAIL';



$expect = '<h2>Main title</h2>

<h2>2nd title</h2>

<blockquote>
  <p>Quote</p>
</blockquote>

<pre><code># this is code
</code></pre>

<p><a href="http://github.com/">Github</a></p>';

$view = Tailor\Base::partial('sample.html');
$test = trim($view);

echo "\nPARTIAL-TEST: ";
echo $expect === $test ? 'OK' : 'FAIL';

echo "\n";
