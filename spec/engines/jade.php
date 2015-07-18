<?php

describe('Jade', function() {
  it('should parse .jade source', function() {
    $tpl = 'h1 ok';

    $view = load('jade', $tpl);

    expect($view)->toMatch('|<h1>\s*ok\s*</h1>|');
  });

  it('should load .jade sources', function() {
    $view = render(partial('../views/example2.html'));

    expect($view)->toContain(phpversion());
    expect($view)->toContain('<!DOCTYPE html>');
    expect($view)->toContain("<div id='container'>");
  });
});
