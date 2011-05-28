##  About

This plugin aims to add support for easing the most common things when using the framework - or an application built upon it - as a designer.

## Locating templates

An extended View class adds HTML comments to all rendered templates and elements, showing where each individual template file starts and ends. This is useful when cutting up or modifying designs.

The View class will be automatically enabled through a filter in the plugin's `config/bootstrap.php` file.

## Generating placeholder text and images

The `Dummy` helper generates filler text and placeholder images. Since helpers are automatically and lazy loaded in Lithium you just need to start using it via `$this->dummy` in your templates.

```php
// Generate random pseudo latin filler text with 400 words.
echo $this->dummy->text(400);

// Text starts with the well kown Lorem ipsum...
echo $this->dummy->text(400, array(
    'lorem' => true
));

// Using other than the default `.` and `,` punctuation symbols.
echo $this->dummy->text(400, array(
    'symbols' => '.,:;'
));

// Generate a HTML img tag with a 200x500 placeholder image.
echo $this->dummy->image(200, 500);

// Control foreground and background color.
echo $this->dummy->image(200, 500, array(
    'background' => 'a6a6a6',
    'foreground' => 'bbb999'
));

// Custom text.
echo $this->dummy->image(200, 500, array(
    'text' => 'Hello world!',
));
}}}

## Download

  Clone the repository with `git clone code@rad-dev.org:li3_design.git`.
  In order to be able to clone you must register an account on rad-dev.org
  (this helps with getting involved i.e. for filing tickets later). Get help 
  on how to do so at http://rad-dev.org/wiki/guides/setup.

  You can also download versions as archives
  at http://rad-dev.org/li3_design/versions.

## Requirements

The plugin's _master_ branch runs fine with the most recent stable Lithium release. Topic branches are named according to the Lithium version they require (i.e. _0.7_ branch requires 0.7).

## Installation

  In case you downloaded extract it first. You now have to register your plugin 
  as a library within your application in `config/bootstrap/libraries.php`.

  Just add `Library::add('li3_design');` at the bottom of the file. Should your 
  plugin not be located in one of the library paths (i.e. `<app>/libraries`)
  you must also specify a path `Library::add('li3_design', array('path' => ...));`.


## Copyright & License

Design Plugin for Lithium is Copyright (c) 2010, Union of RAD
if not otherwise stated. The code is distributed under the terms
of the BSD License. For the full license text see the LICENSE.txt 
file.

## Contributing and future plans

If you're interested in adding more features to this plugin, we are all ears. Join us in #li3 on freenode and tell us what you think. Below a list of ideas which should serve as an inspiration to you and us ;)

- Some kind of grid support (i.e. http://www.1kbgrid.com)
- Command for extracting all used colors from CSS files and generate a color palette (in HTML format or just output codes as plaintext).
- Commonly used elements?
