<?php

describe('Twig', function() {
  it('should parse .twig source', function() {
    $tpl = '<ul>
{% for i in 1..3 %}
  <li>Item n{{ loop.index }}</li>
{% endfor %}
</ul>';

    $view = load('twig', $tpl);
    $view = @eval('?' . ">$view");

    $test = '<ul>
  <li>Item n1</li>
  <li>Item n2</li>
  <li>Item n3</li>
</ul>';

    expect($view(array()))->toContain($test);
  });

  it('should load .twig sources', function() {
    $view = render(partial('example4.php'), array('foo' => array('bar' => 'xD')));

    $test = '<span>
  This is xD!!
</span>';

    expect($view)->toContain($test);
  });
});
