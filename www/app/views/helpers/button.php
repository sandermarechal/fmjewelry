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
 * A Helper that renders graphical buttons
 */
class ButtonHelper extends AppHelper
{
	/** @var string Path to the TTF to use inside APP. With leading slash */
	public $ttf = '/fonts/josefin.ttf';

	/** @var int Size of the font in pt */
	public $size = 14;

	/** @var string Path to the left side of the button in APP. */
	public $img_left = '';

	/** @var string Path to the tiled center of the button in APP. */
	public $img_center = '';

	/** @var string Path to the right side of the button in APP. */
	public $img_right = '';

	/** @var string Path to the storage directory inside app/webroot, including trailing slash, but no leading slash */
	public $path = 'img/buttons/';

	/** @var array Font color in RGB */
	public $color = array(0, 0, 0);

	/** @var array Use the HTML helper */
	public $helpers = array('Html');

	/**
	 * Render a button image
	 * @param string $text The button text
	 */
	public function img($text)
	{
		$path = $this->_getPath($text);
		if (!file_exists(WWW_ROOT . $path) || Configure::read('debug') > 0) {
			$this->_render($text);
		}

		return $this->output("<img class=\"button\" src=\"/{$path}\" alt=\"{$text}\" />");
	}

	/**
	 * Render an image link
	 * 
	 * If $url starts with "http://" this is treated as an external link. Else,
	 * it is treated as a path to controller/action and parsed with the
	 * HtmlHelper::url() method.
	 *
	 * If the $url is empty, $title is used instead.
	 *
	 * @param  string  $title The text on the button image to be wrapped by <a> tags.
	 * @param  mixed   $url Cake-relative URL or array of URL parameters, or external URL (starts with http://)
	 * @param  array   $htmlAttributes Array of HTML attributes.
	 * @param  string  $confirmMessage JavaScript confirmation message.
	 * @param  boolean $escapeTitle	Whether or not $title should be HTML escaped.
	 * @return string  An <a /> element wrapping an image button.
	 */
	public function link($title, $url = null, $htmlAttributes = array(), $confirmMessage = false)
	{
		if (!isset($htmlAttributes['escape']) || $htmlAttributes['escape']) {
			$title = h($title);
			$htmlAttributes['escape'] = false;
		}

		return $this->output($this->Html->link($this->img($title), $url, $htmlAttributes, $confirmMessage));
	}

	/**
	 * Render a submit button
	 * @param string $text The button text
	 * @param string $options An options array
	 *
	 * $option['div'] Default true. Render a div with class "submit" around the button.
	 * $option['name'] Default null. Set the "name" attribute
	 */
	public function submit($text, $options = array())
	{
		$path = $this->_getPath($text);
		if (!file_exists(WWW_ROOT . $path) || Configure::read('debug') > 0) {
			$this->_render($text);
		}

		$attrs = array(
			'type' => 'image',
			'value' => $text,
			'class' => 'button',
			'src' => '/' . $path,
			'alt' => $text,
		);

		if (isset($options['name'])) {
			$attrs['name'] = $options['name'];
		}

		$output = '<input';
		foreach ($attrs as $name => $value) {
			$output .= sprintf(' %s="%s"', $name, $value);
		}
		$output .= ' />';

		if (!isset($options['div']) || (isset($options['div']) && $options['div'] == true)) {
			$output = sprintf('<div class="submit">%s</div>', $output);
		}


		return $this->output($output);
	}

	/**
	 * Return the URL to the image
	 * @param string $text The button text
	 */
	public function url($text)
	{
		$path = $this->_getPath($value);
		if (!file_exists(WWW_ROOT . $path) || Configure::read('debug') > 0) {
			$this->_render($value);
		}

		return '/' . $path;
	}

	/**
	 * Get the path to a button image
	 * @param string $text The button text
	 */
	private function _getPath($text)
	{
		$file = strtolower(Inflector::slug($text)) . '.png';
		return $this->path . $file;
	}

	/**
	 * Render an image of the button
	 * @param string $text The text on the button
	 */
	private function _render($text)
	{
		// Open the images
		$left   = $this->img_left   ? imagecreatefrompng(APP . $this->img_left)   : false;
		$center = $this->img_center ? imagecreatefrompng(APP . $this->img_center) : false;
		$right  = $this->img_right  ? imagecreatefrompng(APP . $this->img_right)  : false;

		// Get image dimensions
		$left_sx   = $left   ? imagesx($left)   : 0; $left_sy   = $left   ? imagesy($left)   : 0;
		$center_sx = $center ? imagesx($center) : 0; $center_sy = $center ? imagesy($center) : 0;
		$right_sx  = $right  ? imagesx($right)  : 0; $right_sy  = $right  ? imagesy($right)  : 0;

		// Calculate button text dimensions
		$sizes  = imageftbbox($this->size, 0.0, APP . $this->ttf, $text);
		$text_width = $sizes[2] - $sizes[0];
		$text_height = abs($sizes[5]) + $sizes[3];
		$text_baseline = $sizes[1];

		// Create a new image
		$width  = $text_width + $left_sx + $right_sx + (2 * $this->size);
		$height = max($text_height, $left_sy, $center_sy, $right_sy);

		$image = imagecreatetruecolor($width, $height);
		$white = imagecolorallocate($image, 255, 255, 255);
		$color = imagecolorallocate($image, $this->color[0], $this->color[1], $this->color[2]);

		imagecolortransparent($image, $white);
		imagefilledrectangle($image, 0, 0, $width, $height, $white);

		// Fill tiled with center image
		if ($center) {
			for ($x = 0; $x < imagesx($image); $x += $center_sx) {
				imagecopy($image, $center, $x, 0, 0, 0, imagesx($center), imagesy($center));
			}
		}

		// Add left side
		if ($left) {
			imagecopy($image, $left, 0, 0, 0, 0, $left_sx, $left_sy);
		}

		// Add right side
		if ($right) {
			imagecopy($image, $right, $width - $right_sx, 0, 0, 0, $right_sx, $right_sy);
		}

		// Add the text
		$text_x = $left_sx + $this->size;
		$text_y = $height - (($height - $text_height) / 2) - $text_baseline;
		imagettftext($image, $this->size, 0.0, $text_x, $text_y, $color, APP . $this->ttf, $text);
		imagepng($image, $this->_getPath($text));

		if ($left)   { imagedestroy($left);   }
		if ($center) { imagedestroy($center); }
		if ($right)  { imagedestroy($right);  }
		imagedestroy($image);
	}
}

?>
