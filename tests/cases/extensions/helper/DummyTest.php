<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2010, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_design\tests\cases\extensions\helper;

use \li3_design\extensions\helper\Dummy;
use \lithium\tests\mocks\template\helper\MockHtmlRenderer;

class DummyTest extends \lithium\test\Unit {

	public $helper = null;

	public function setUp() {
		$this->context = new MockHtmlRenderer();
		$this->helper = new Dummy(array('context' => &$this->context));
	}

	public function testTextWithoutOptions() {
		$result = $this->helper->text(20);
		$this->assertPattern('/^([a-z]+([\.,]\s|\s|\.)+){20}$/i', $result);
	}

	public function testTextCasing() {
		$result = $this->helper->text(20);
		$this->assertPattern("/((^|\.\s)[A-Z])/", $result);
	}

	public function testTextSymbolsDisabled() {
		$result = $this->helper->text(40, array('symbols' => false));
		$this->assertPattern('/^([a-z]+\s?){40}$/i', $result);
	}

	public function testTextSymbolsCustom() {
		$result = $this->helper->text(500, array('symbols' => '.:;,'));
		$this->assertPattern('/^([a-z]+([\.,;:]\s|\s|\.)+){500}$/i', $result);
	}

	public function testTextSymbolsChance() {
		$resultA = $this->helper->text(100, array('chance' => 10, 'symbols' => '.'));
		$resultA = substr_count($resultA, '.');

		$resultB = $this->helper->text(100, array('chance' => 70, 'symbols' => '.'));
		$resultB = substr_count($resultB, '.');

		$this->assertTrue($resultA < $resultB);
	}

	public function testTextPrependLorem() {
		$lorem = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. ';

		$result = $this->helper->text(40, array('lorem' => true));
		$this->assertTrue(strpos($result, $lorem) === 0);

		$result = $this->helper->text(40, array('lorem' => true, 'symbols' => false));
		$this->assertTrue(strpos($result, $lorem) === 0);
	}

	public function testImage() {
		$this->skipIf(!extension_loaded('gd'), 'The GD extesnion is not available.');

		$result = $this->helper->image(10, 15);
		$expected = array(
			'img' => array(
				'src' => 'regex:/data\:image\/png;base64,[A-Za-z0-9\/\+=]+/',
				'width' => 10,
				'height' => 15,
				'alt' => 'dummy image'
			),
		);
		$this->assertTags($result, $expected);

		$result = $this->helper->image(10, 15);
		preg_match('/base64,([A-Za-z0-9\/\+=]+)/', $result, $matches);

		$data = base64_decode($matches[1]);
		$image = imageCreateFromString($data);

		$result = imagesx($image);
		$expected = 10;
		$this->assertEqual($expected, $result);

		$result = imagesy($image);
		$expected = 15;
		$this->assertEqual($expected, $result);

		imagedestroy($image);
	}

}

?>
