<?php

describe('SCSS', function() {
  it('should parse .scss source', function() {
    $tpl = '
    @mixin foo {
      candy: "bar";
      $font: asset-path("dummy.ttf");
      value: url(\'#{$font}?x#y\');
      width: image-width("php.png");
      background: asset-url("php.png");
      $data: asset-data-uri("px.gif");
      other: url("#{$data}");
    }

    .x { @include foo; }
    ';

    $view = load('scss', $tpl);

    $test = '.x {
  candy: "bar";
  value: url(\'/static/font/dummy.ttf?x#y\');
  width: 32px;
  background: url("/static/img/php.png");
  other: url("data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"); }';

    expect($view)->toContain($test);
  });

  it('should load .scss sources', function() {
    $view = render(partial('../styles/stylescss.css'));

    $test = '.x:after {
  content: "' . (float) phpversion() . '"; }';

    expect($view)->toContain($test);
  });
});
