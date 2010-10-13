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
 * A Helper that renders graphical first-letter divs
 */
class LetterHelper extends AppHelper
{
	/** @var string Path to the TTF to use inside APP. With leading slash */
	public $ttf = '/fonts/josefin.ttf';

	/** @var string Path to the storage directory inside app/webroot, including trailing slash, but no leading slash */
	public $path = 'img/letters/';

	/** @var array Font color in RGB */
	public $color = array(238, 51, 34);

	/**
	 * Render a header
	 * @param int $level The header level (1-6)
	 * @param string $text The header text
	 */
	public function img($text)
	{
		$path = $this->_getPath($text);
		if (!file_exists(WWW_ROOT . $path) || Configure::read('debug') > 0) {
			$this->_render($text);
		}

		$info = @getimagesize(WWW_ROOT . $path);
		$width = $info[0];
		$height = $info[1];

		return $this->output("<img class=\"first-letter\" style=\"width: {$width}px; height: {$height}px;\" src=\"/{$path}\" />");
	}

	/**
	 * Get the path to a header image
	 * @param int $level The header level (1-6)
	 * @param string $text The header text
	 */
	private function _getPath($text)
	{
		$file = strtolower(Inflector::slug($text)) . '.png';
		return $this->path . $file;
	}

	/**
	 * Render an image of the header
	 * @param int $level The header level (1-6)
	 * @param string $text The header text
	 */
	private function _render($text)
	{
		$sizes = imageftbbox(38, 0.0, APP . $this->ttf, $text);
		$width = $sizes[2] - $sizes[0];
		$height = abs($sizes[5]) + $sizes[3];
		$baseline = $sizes[1];

		$image = imagecreatetruecolor($width, $height);
		$white = imagecolorallocate($image, 255, 255, 255);
		$color = imagecolorallocate($image, $this->color[0], $this->color[1], $this->color[2]);
		imagecolortransparent($image, $white);

		imagefilledrectangle($image, 0, 0, $width, $height, $white);
		imagettftext($image, 38, 0.0, 0, $height - $baseline, $color, APP . $this->ttf, $text);
		imagepng($image, $this->_getPath($text));
		imagedestroy($image);
	}
}

?>
