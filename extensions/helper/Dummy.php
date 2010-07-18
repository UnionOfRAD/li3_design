<?php
/**
 * Lithium: the most rad php framework
 *
 * The `image()` method is based upon the "Dynamic Dummy Image
 * Generator - DummyImage.com", Copyright (c) 2010 Russell Heimlich.
 *
 * @copyright     Copyright 2010, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_design\extensions\helper;

/**
 * The `Dummy` helper allows you to generate filler text and placeholder images.
 */
class Dummy extends \lithium\template\Helper {

	/**
	 * String templates used by this helper.
	 *
	 * @var array
	 */
	protected $_strings = array(
		'image' => '<img src="data:{:type};base64,{:data}"{:options} />',
	);

	/**
	 * Used by output handlers to calculate asset paths in conjunction with the `Media` class.
	 *
	 * @var array
	 * @see lithium\net\http\Media
	 */
	public $contentMap = array(
		'image' => 'image'
	);


	/**
	 * Generates a pseudo latin filler text. This text obiously
	 * makes no sense at all (which is the intention).
	 *
	 * @param integer $words Number of words to gernerate.
	 * @param array $options Valid options are:
	 *        - `'lorem'`: If `true` forces text to start with `Lorem ipsum...`, defaults
	 *                     to `false`. The formatting and amount of words of the well know
	 *                     beginning may not match or exceed other settings.
	 *        - `'symbols'`: A list of characters to use for injecting punctuation symbols.
	 *                       Defaults to `'.,'`. If set to `false` won't do any punctuation
	 *                       at all.
	 *        - `'chance'`: The chance (n out of 100) that given a mininium of `distance` to
	 *                      the last symbol is reached a punctuation symbol is used. Defaults
	 *                      to `15`.
	 *        - `'distance'`: The mininum distance of words between symbols. Defaults to `5`.
	 * @return string
	 */
	public function text($words, array $options = array()) {
		$defaults = array(
			'lorem' => false,
			'symbols' => '.,',
			'chance' => 15,
			'distance' => 5
		);
		$options += $defaults;

		$data = <<<LOREM
lorem ipsum dolor sit amet consectetur adipisici elit sed eiusmod tempor incidunt ut labore et
dolore magna aliqua enim ad minim veniam quis nostrud exercitation ullamco laboris nisi aliquid ex
ea commodi consequat aute iure reprehenderit in voluptate velit esse cillum eu fugiat nulla pariatur
excepteur sint obcaecat cupiditat non proident sunt culpa qui officia deserunt mollit anim id est
laborumduis autem vel eum iriure hendrerit vulputate molestie illum feugiat facilisis at vero eros
accumsan iusto odio dignissim blandit praesent luptatum zzril delenit augue duis te feugait facilisi
consectetuer adipiscing diam nonummy nibh euismod tincidunt laoreet aliquam erat volutpatut wisi
exerci tation ullamcorper suscipit lobortis nisl aliquip commodo facilisinam liber cum soluta nobis
eleifend option congue nihil imperdiet doming quod mazim placerat facer possim assum volutpat
consequatduis facilisisat eos accusam justo duo dolores rebum stet clita kasd gubergren no sea
takimata sanctus consetetur sadipscing elitr nonumy eirmod invidunt aliquyam voluptua eratconsetetu
LOREM;
		$data = preg_split('/\s+/', $data);

		for ($i = 0, $max = count($data) - 1; $i < $words; $i++) {
			$random[] = $data[rand(0, $max)];
		}

		$lastSymbol = 0;
		$result = '';

		if ($options['lorem']) {
			$result .= 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. ';
		}
		$result .= ucfirst(array_pop($random));

		while ($random) {
			$item = array_pop($random);

			if (substr($result, -1) === '.') {
				$item = ucfirst($item);
			}
			$result .= ' ' . $item;

			if ($options['symbols']) {
				if ($lastSymbol >= $options['distance'] && rand(1, 100) <= $options['chance'] && $random) {
					$lastSymbol = 0;
					$result .= $options['symbols'][rand() % strlen($options['symbols'])];
				} else {
					$lastSymbol++;
				}
			}
		}
		if (strpos($options['symbols'], '.') !== false) {
			$result .= '.';
		}
		return $result;
	}

	/**
	 * Generates a tag with a placeholder image using the GD extension. In case
	 * the extension isn't available the method will just return `null`.
	 *
	 * @param integer $width The width of the image.
	 * @param integer $height The height of the image.
	 * @param array $options Valid options are:
	 *        - `'background'`: Color to use for background in hex representation
	 *                          without leading `'#'`, defaults to `'0099ff'`.
	 *        - `'foreground'`: Color to use as the text color in hex representation
	 *                          without leading `'#'`, defaults to `'ffffff'`.
	 * @return string A HTML image tag with a data URI for the placeholder image.
	 */
	public function image($width, $height, array $options = array()) {
		if (!extension_loaded('gd')) {
			return;
		}

		$defaults = array(
			'background' => '0099ff',
			'foreground' => 'ffffff',
			'format' => 'png', // png, gif or jpg
			'text' => "{$width} × {$height} px"
		);
		$options += $defaults;
		$image = imageCreate($width, $height);

		$bgColor = $this->_hexToRgb($options['background']);
		$fgColor = $this->_hexToRgb($options['foreground']);
		$bgColor = imageColorAllocate($image, $bgColor['r'], $bgColor['g'], $bgColor['b']);
		$fgColor = imageColorAllocate($image, $fgColor['r'], $fgColor['g'], $fgColor['b']);

		/* Scale the text size. */
		$fontSize = max(min($width / strlen($options['text']) * 1.15, $height * 0.5), 5);
		$fontFile = dirname(dirname(__DIR__)) . '/resources/mplus-1c-medium.ttf';

		/* Compute text bounding box size with a zero angle. */
		$textBox = imageTtfBbox($fontSize, 0, $fontFile, $options['text']);

		$textWidth = ceil(($textBox[4] - $textBox[1]) * 1.07);
		$textHeight = ceil((abs($textBox[7]) + abs($textBox[1])) * 1);

		/* Determines where to set the X and Y position of the text box so it is centered. */
		$textX = ceil(($width - $textWidth) / 2);
		$textY = ceil(($height - $textHeight) / 2 + $textHeight);

		imageFilledRectangle($image, 0, 0, $width, $height, $bgColor);

		/* Create and positions the text. */
		imageTtfText($image, $fontSize, 0, $textX, $textY, $fgColor, $fontFile, $options['text']);

		if ($options['format'] == 'jpg') {
			$options['format'] = 'jpeg';
		}

		ob_start();
		call_user_func("image{$options['format']}", $image);
		$blob = ob_get_clean();

		imageDestroy($image);

		$data = base64_encode($blob);
		$type = "image/{$options['format']}";
		$options = compact('width', 'height') + array('alt' => 'dummy image');

		return $this->_render(__METHOD__, 'image', compact('type', 'data', 'options'));
	}

	/**
	 * Calculates the RGB values for a color in hex format.
	 *
	 * @param string $value Color in hex format (without leading `#`).
	 * @return array An array with the key `'r'`, `'g'`, `'b'` (in that order).
	 */
	protected function _hexToRgb($value) {
		$value = hexdec($value);

		return array(
			'r' => 0xFF & ($value >> 0x10),
			'g' => 0xFF & ($value >> 0x8),
			'b' => 0xFF & $value
		);
	}
}

?>