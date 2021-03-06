<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2011, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

use lithium\net\http\Media;

/**
 * Register the design view as the default handler.
 *
 * @see li3_design\extensions\View
 */
Media::type('default', null, array(
	'view' => 'li3_design\extensions\View',
	'paths'    => array(
		'template' => '{:library}/views/{:controller}/{:template}.{:type}.php',
		'layout'   => '{:library}/views/layouts/{:layout}.{:type}.php',
		'element'  => '{:library}/views/elements/{:template}.{:type}.php'
	)
));

?>