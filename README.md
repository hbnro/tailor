What's Tailor?
--------------

Provides a common templating interface for partial rendering.

It comes with support for Twig, CoffeeScript, HAML, SCSS, LESS and Markdown out of the box. Written using Composer standards, PSR-friendly.


## Using the composer

Just declare it on your composer.json file.

    {
      "require": {
        "habanero/tailor": "dev-master"
      }
    }

Then execute the composer to download and setup the latest version.

    $ php composer.phar install


You can use Tailor along with your other composer scripts.

    <?php

    require 'vendor/autoload.php';

    # configure before
    Tailor\Config::set('cache_dir', '/tmp');
    Tailor\Config::set('views_dir', __DIR__);

    # go ahead!
    Tailor\Base::initialize();

    # raw rendering
    $tpl = '%a{ :href => "#" } Link';
    $out = Tailor\Base::parse('haml', $tpl);

    echo $out; // <a href="#">Link</a>


    # using as helper
    function partial($path, array $vars = array()) {
      $tpl = Tailor\Base::partial($path);
      $out = Tailor\Base::render($tpl, $vars);

      return $out;
    }

    # full-view rendering
    $out = partial('index.php', array('name' => 'Joe'));

    echo $out; // Hello Joe!

If you want to use HAML with the code above you must have a
file named `index.php.haml` on your specified `./views`
directory.

    = "Hello $name!"

The template evaluation order its from right to left, it will stop
if the filename contains one extension or the extension is not
within any registered engine.

    index.php.haml => haml-engine … continue
    index.php => php-engine … stop!

Tailor's partial() method will only compile in order to preserve
the original nature from its first extension, this is the main
reason for using the render() method to finally get rendered our template.


## Just fork-me!

Of course you're welcome to fork and send pull-request to add more engines, fixes or enhancement features. ;-)
