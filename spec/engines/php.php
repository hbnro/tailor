<?php

describe('PHP', function() {
  it('should parse .php source', function() {
    $tpl = '<?php echo phpversion();';

    $view = load('php', $tpl);

    expect(trim($view))->toEqual(phpversion());
  });

  it('should load .php sources', function() {
    $view = render(partial('index.html'));

    expect($view)->toContain('<p>It works!</p>');
  });
});
