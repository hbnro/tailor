<?php

\Tailor\Base::initialize();

describe('Engines', function() {
  it('should parse Markdown', function() {
    $view = \Tailor\Base::parse('markdown', '[Link](#)');

    expect($view)->toContain('<p><a href="#">Link</a></p>');
  });
});
