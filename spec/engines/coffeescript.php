<?php

describe('CoffeeScript', function() {
  it('should parse .coffee source', function() {
    $tpl = 'foo = ->
  candy "bar"';

    $view = load('coffee', $tpl);

    expect($view)->toContain('return candy("bar");');
  });

  it('should load .coffee sources', function() {
    $view = partial('../scripts/script.js');
    $code = render($view);

    $test = '(function($) {
  return $(function() {
    return console.log("' . (float) phpversion() . '");
  });
})(jQuery);';

    expect($code)->toContain($test);
  });
});
