<?php
/**
 * Full Metal Jewelry website
 * Copyright (C) 2010 Stichting Lone Wolves
 * Written by Sander Marechal <s.marechal@jejik.com>
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 */

/**
 * A Helper that renders graphical headers
 */
class HeaderHelper extends AppHelper
{
	/** @var string Path to the TTF to use inside APP. With leading slash */
	public $ttf = '/fonts/josefin.ttf';

	/** @var string Path to the storage directory inside app/webroot, including trailing slash, but no leading slash */
	public $path = 'img/headers/';

	/** @var array Point sizes for the header */
	public $levels = array(1 => 38, 32, 28, 24, 20, 16);

	/** @var array Font color in RGB */
	//public $color = array(238, 51, 34);
	public $color = array(0, 0, 0);

	/** @var string Path to the divider image in APP. Leave empty to disable. */
	public $divider = '';

	/** @var integer How often to repeat the divider */
	public $divider_repeat = 3;

	/** @var integer Width of the header image. Only used when a divider needs to be added */
	public $full_width = 600;

	/**
	 * Render a header
	 * @param int $level The header level (1-6)
	 * @param string $text The header text
	 * @param boolean $add_divider Render divider
	 */
	public function header($level, $text, $add_divider = true)
	{
		$path = $this->_getPath($level, $text, $add_divider);
		if (!file_exists(WWW_ROOT . $path) || Configure::read('debug') > 0) {
			$this->_render($level, $text, $add_divider);
		}

		$info = @getimagesize(WWW_ROOT . $path);
		$height = $info[1];

		return $this->output("<h{$level} class=\"header-replace\" style=\"height: {$height}px; background-image: url(/{$path});\">{$text}</h{$level}>");
	}

	/**#@+
	 * Shortcut function for header()
	 * @param int $level The header level (1-6)
	 * @param string $text The header text
	 * @param boolean $add_divider Render divider
	 */
	public function h1($text, $add_divider = true) { return $this->header(1, $text, $add_divider); }
	public function h2($text, $add_divider = true) { return $this->header(2, $text, $add_divider); }
	public function h3($text, $add_divider = true) { return $this->header(3, $text, $add_divider); }
	public function h4($text, $add_divider = true) { return $this->header(4, $text, $add_divider); }
	public function h5($text, $add_divider = true) { return $this->header(5, $text, $add_divider); }
	public function h6($text, $add_divider = true) { return $this->header(6, $text, $add_divider); }
	/**#@-*/

	/**
	 * Get the path to a header image
	 * @param int $level The header level (1-6)
	 * @param string $text The header text
	 * @param boolean $add_divider Render divider
	 */
	private function _getPath($level, $text, $add_divider = true)
	{
		$add_divider = (int) $add_divider;
		$file = $level . '-' . $add_divider . '-' . strtolower(Inflector::slug($text)) . '.png';
		return $this->path . $file;
	}

	/**
	 * Render an image of the header
	 * @param int $level The header level (1-6)
	 * @param string $text The header text
	 * @param boolean $add_divider Render divider
	 */
	private function _render($level, $text, $add_divider = true)
	{
		$sizes = imageftbbox($this->levels[$level], 0.0, APP . $this->ttf, $text);
		$width = $sizes[2] - $sizes[0];
		$height = abs($sizes[5]) + $sizes[3];
		$baseline = $sizes[1];

		if ($this->divider && $add_divider) {
			$width = $this->full_width;
			$height += $this->levels[$level]; // Spacing between divider and header
		}

		// Create the header image
		$image = imagecreatetruecolor($width, $height);
		$white = imagecolorallocate($image, 255, 255, 255);
		$color = imagecolorallocate($image, $this->color[0], $this->color[1], $this->color[2]);
		imagecolortransparent($image, $white);
		imagefilledrectangle($image, 0, 0, $width, $height, $white);

		// Add the divider
		if ($this->divider && $add_divider) {
			$divider = imagecreatefrompng(APP . $this->divider);
			$divider_width = $this->divider_repeat * imagesx($divider) + ($this->divider_repeat - 1) * $this->levels[$level];
			$divider_offset = imagesx($divider) + $this->levels[$level];
			$divider_x = ($width - $divider_width) / 2;

			for ($i = 0; $i < $this->divider_repeat; $i++) {
				imagecopy($image, $divider, $divider_x, 0, 0, 0, imagesx($divider), imagesy($divider));
				$divider_x += $divider_offset;
			}

			imagedestroy($divider);
		}

		// Add the text
		imagettftext($image, $this->levels[$level], 0.0, 0, $height - $baseline, $color, APP . $this->ttf, $text);
		imagepng($image, $this->_getPath($level, $text, $add_divider));
		imagedestroy($image);
	}
}

?>
