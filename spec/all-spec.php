<?php

\Tailor\Base::initialize();

function load($engine, $code) {
  return \Tailor\Base::parse($engine, $code);
}

describe('Engines', function() {
  it('should parse Markdown', function() {
    $view = load('markdown', '[Link](#)');

    expect($view)->toContain('<p><a href="#">Link</a></p>');
  });

  it('should parse Jade', function() {
    $view = load('jade', 'h1 ok');

    expect($view)->toMatch('|<h1>\s*ok\s*</h1>|');
  });
});
