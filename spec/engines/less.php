<?php

describe('LESS', function() {
  it('should parse .less source', function() {
    $tpl = '
    .foo() {
      candy: "bar";
      @font: asset-path("dummy.ttf");
      value: url(\'@{font}?x#y\');
      width: image-width("php.png");
      background: asset-url("php.png");
      @data: asset-data-uri("px.gif");
      other: url("@{data}");
    }

    .x { .foo; }
    ';

    $view = load('less', $tpl);

    $test = '.x {
  candy: "bar";
  value: url(\'/static/font/dummy.ttf?x#y\');
  width: 32px;
  background: url("/static/img/php.png");
  other: url("data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7");
}';

    expect($view)->toContain($test);
  });

  it('should load .less sources', function() {
    $view = render(partial('../styles/styless.css'));

    $test = '.x:after {
  content: "' . (float) phpversion() . '";
}';

    expect($view)->toContain($test);
  });
});
