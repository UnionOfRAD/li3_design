<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2010, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_design\extensions;

use \lithium\core\Libraries;
use \lithium\core\Environment;
use \lithium\util\String;

/**
 * This extended View class adds HTML comments to all rendered templates and
 * elements, showing where each individual template file starts and ends. This
 * is useful for designers and front-end integrators when cutting up or
 * modifying designs.
 */
class View extends \lithium\template\View {

	/**
	 * The 'element' render type handler.
	 *
	 * @param string $template Template to be rendered.
	 * @param array $data Template data.
	 * @param array $options Renderer options.
	 */
	protected function _element($template, $data, array $options = array()) {
		$options += array('controller' => 'elements', 'template' => $template);
		$template = $this->_loader->template('template', $options);
		$data = (array) $data + $this->outputFilters;
		$data = $this->_renderer->render($template, $data, $options);
		return $this->_pathized($data, $template);
	}

	/**
	 * The 'template' render type handler.
	 *
	 * @param string $template Template to be rendered.
	 * @param array $data Template data.
	 * @param array $options Renderer options.
	 */
	protected function _template($template, $data, array $options = array()) {
		$template = $this->_loader->template('template', $options);
		$data = (array) $data + $this->outputFilters;
		$data = $this->_renderer->render($template, $data, $options);
		return $this->_pathized($data, $template);
	}

	/**
	 * Wrap the passed data with HTML comments.
	 *
	 * @param string $data The data to be wrapped.
	 * @param string $path The path to the template that corresponds to the data.
	 * @return string Data wrapped by HTML comments.
	 */
	protected function _pathized($data, $path) {
		if (Environment::is('production')) {
			return $data;
		}
		$message = "<!-- START OF {:start} -->\n{$data}\n<!-- END OF {:stop} -->";
		return String::insert($message, array('start' => $path, 'stop' => $path));
	}
}

?>