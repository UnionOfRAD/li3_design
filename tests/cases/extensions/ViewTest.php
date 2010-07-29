<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2010, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_design\tests\cases\extensions;

use \li3_design\extensions\View;
use \lithium\g11n\catalog\adapter\Memory;
use \lithium\template\view\adapter\Simple;
use \lithium\template\view\adapter\File;

class TestViewClass extends \lithium\template\View {

	public function renderer() {
		return $this->_config['renderer'];
	}
}

class ViewTest extends \lithium\test\Unit {

	protected $_view = null;

	public function setUp() {
		$this->_view = new View();
	}

	public function testBasicRenderModes() {
		$view = new View(array('loader' => 'Simple', 'renderer' => 'Simple'));

		$result = $view->render('template', array('content' => 'world'), array(
			'template' => 'Hello {:content}!'
		));
		$expected  = "<!-- START OF template -->\n";
		$expected .= "Hello world!";
		$expected .= "\n<!-- END OF template -->";
		$this->assertEqual($expected, $result);

		$result = $view->render(array('element' => 'Logged in as: {:name}.'), array(
			'name' => "Cap'n Crunch"
		));
		$expected  = "<!-- START OF element -->\n";
		$expected .= "Logged in as: Cap'n Crunch.";
		$expected .= "\n<!-- END OF element -->";
		$this->assertEqual($expected, $result);

		$xmlHeader = '<' . '?xml version="1.0" ?' . '>' . "\n";
		$result = $view->render('all', array('type' => 'auth', 'success' => 'true'), array(
			'layout' => $xmlHeader . "\n{:content}\n",
			'template' => '<{:type}>{:success}</{:type}>'
		));
		$expected  = "{$xmlHeader}\n";
		$expected .= "<!-- START OF template -->\n";
		$expected .= "<auth>true</auth>";
		$expected .= "\n<!-- END OF template -->";
		$expected .= "\n";
		$this->assertEqual($expected, $result);
	}

	public function testRenderWithFile() {
		$path = LITHIUM_APP_PATH . '/resources/tmp/tests';
		$this->skipIf(!is_writable($path), "{$path} is not writable.");

		$file = "{$path}/test.html.php";
		$data = 'Hello world!';
		file_put_contents($file, $data);

		$adapter = new File(array('paths' => array('template' => "{$path}/{:name}")));
		$view = new View(array('loader' => $adapter, 'renderer' => $adapter));

		$result = $view->render('template', null, array('name' => 'test.html.php'));
		$expected  = "<!-- START OF template {$file} -->\n";
		$expected .= "Hello world!";
		$expected .= "\n<!-- END OF template {$file} -->";
		$this->assertEqual($expected, $result);

		$this->_cleanUp();
	}

	public function testFullRenderNoLayout() {
		$view = new View(array('loader' => 'Simple', 'renderer' => 'Simple'));
		$result = $view->render('all', array('type' => 'auth', 'success' => 'true'), array(
			'template' => '<{:type}>{:success}</{:type}>'
		));
		$expected  = "<!-- START OF template -->\n";
		$expected .= '<auth>true</auth>';
		$expected .= "\n<!-- END OF template -->";
		$this->assertEqual($expected, $result);
	}
}

?>