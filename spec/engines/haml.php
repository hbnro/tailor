<?php

describe('HAML', function() {
  it('should parse .haml source', function() {
    $tpl = '%a{ :href => "#" } Link';

    $view = preg_replace('/\s+/', '', load('haml', $tpl));

    expect($view)->toEqual('<ahref="#">Link</a>');
  });

  it('should load .haml sources', function() {
    $view = preg_replace('/\s+/', '', render(partial('example.html')));
    $test = '<!DOCTYPEhtml><html><head><title>' . phpversion() . '</title></head><body><h1>OK</h1><divid="container"><p>bar</p></div></body></html>';

    expect($view)->toEqual($test);
  });
});
