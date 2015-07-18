<?php

describe('Markdown', function() {
  it('should parse .md source', function() {
    $tpl = '[Link](#)';

    $view = load('markdown', $tpl);

    expect($view)->toContain('<p><a href="#">Link</a></p>');
  });

  it('should load .md sources', function() {
    $view = render(partial('sample.html'));

    $test = '<h2>Main title</h2>

<h2>2nd title</h2>

<blockquote>
  <p>Quote</p>
</blockquote>

<pre><code># this is code
</code></pre>

<p><a href="http://github.com/">Github</a></p>';

    expect($view)->toContain($test);
  });
});
