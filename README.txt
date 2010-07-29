  
  DESIGN
  Plugin for Lithium

  Copyright & License
  -------------------
  Design Plugin for Lithium is Copyright (c) 2010, Union of RAD
  if not otherwise stated. The code is distributed under the terms
  of the BSD License. For the full license text see the LICENSE.txt 
  file.

  Download
  --------
  Clone the repository with `git clone code@rad-dev.org:li3_design.git`.
  In order to be able to clone you must register an account on rad-dev.org
  (this helps with getting involved i.e. for filing tickets later). Get help 
  on how to do so at http://rad-dev.org/wiki/guides/setup.

  You can also download versions as archives
  at http://rad-dev.org/li3_design/versions.
  
  Installation
  ------------
  In case you downloaded extract it first. You now have to register your plugin 
  as a library within your application in `config/bootstrap/libraries.php`.

  Just add `Library::add('li3_design');` at the bottom of the file. Should your 
  plugin not be located in one of the library paths (i.e. `<app>/libraries`)
  you must also specify a path `Library::add('li3_design', array('path' => ...));`.

  About
  -----
  The plugin contains following tools:

  - A `Dummy` helper which is able to generate filler text and placeholder images.
  - A `View` class with template locator.
