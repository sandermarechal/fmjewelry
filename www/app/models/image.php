<?php
/**
 * Feral Penguin Pty Ltd website
 * Copyright (C) 2009 Stichting Lone Wolves
 * Written by Sander Marechal <s.marechal@jejik.com>
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 */

/**
 * The Image Model
 */
class Image extends AppModel
{
	/** @var array The model behaviors */
	public $actsAs = array('Containable');

        /** @var array Image belongs to User */
        public $belongsTo = array('User');

	/** @var array HABTM relations */
        public $hasAndBelongsToMany = array(
                'Product' => array('with' => 'ImagesProduct'),
        );

        /** @var string Use the filename field for display */
        public $displayField = 'filename';

	/**
	 * Save an uploaded image
	 * @param array $image POST data of the uploaded image
	 * @param array &$errors An array where error strings are appended
	 * @return boolean Success
	 */
	public function upload($image, &$errors = array())
	{
		if (!$this->exists() || empty($image)) {
			$errors[] = 'The image does not exists';
			return false;
		}

		if (!$image['name'] || !isset($image['type']) || !$image['size'] || !$image['tmp_name'] || $image['error'] !== 0) {
			$errors[] = 'There was an error during upload. Perhaps the image is too big?';
			return false;
		}

		if (!is_uploaded_file($image['tmp_name'])) {
			$errors[] = 'The image is not an uploaded file. Attempted security exploit?';
			return false;
		}

		$format = strtolower(substr(strrchr($image['name'], '.'), 1));
		if (!in_array($format, array('jpg', 'jpeg', 'png'))) {
			$errors[] = 'Illegal file format "' . $format . '"';
			return false;
		}

                $this->save(array(
                        'format' => $format,
                        'filename' => $image['name'],
                ));

		$destination = WWW_ROOT . DS . 'img' . DS . 'products' . DS . $this->getPath();
		if (!file_exists(dirname($destination)) && !mkdir(dirname($destination), 0775, true)) {
			$errors[] = 'Could not create destination directory';
			return false;
		}

		if (!move_uploaded_file($image['tmp_name'], $destination)) {
			$errors[] = 'Could not move uploaded file';
			return false;
		}

		return true;
	}

	/**
	 * Generate the path to the image
	 * @param integer $width Width of the photo
	 * @return string The path
	 */
	public function getPath($width = false)
	{
		if (!$this->id) {
			return null;
		}

		$width = $width ? (string) $width : 'original';
                $user_id = $this->field('user_id');
		$format = $this->field('format');

                return $user_id . DS . $this->id . '_' . $width . '.' . $format;
	}

	/**
	 * Generate a thumbnail at the required size
	 * @param integer $width Width of the thumbnail
	 */
	public function thumb($width)
	{
		$original_path = WWW_ROOT . DS . 'img' . DS . 'products' . DS . $this->getPath();
		$thumb_path = WWW_ROOT . DS . 'img' . DS . 'products' . DS . $this->getPath($width);

		if (file_exists($thumb_path) && Configure::read('debug') == 0) {
			if (filemtime($original_path) <= filemtime($thumb_path)) {
				return;
			}
		}

		$format = $this->field('format');
		if ($format == 'jpg') {
			$format = 'jpeg';
		}

		$create_func = 'imagecreatefrom' . $format;
		$save_func = 'image' . $format;

		$original = $create_func($original_path);
		if (imagesx($original) >= imagesy($original)) {
			$height = floor($width * (imagesy($original) / imagesx($original)));
		} else {
			$height = $width;
			$width = floor($height * (imagesx($original) / imagesy($original)));
		}
		
		$thumb = imagecreatetruecolor($width, $height);
		imagecopyresampled($thumb, $original, 0, 0, 0, 0, $width, $height, imagesx($original), imagesy($original));
		$save_func($thumb, $thumb_path);

		imagedestroy($original);
		imagedestroy($thumb);
	}

	/**
	 * Delete files when deleting the DB entry
	 * @param boolean $cascade
	 * @return boolean True to continue with the delete
	 */
	public function beforeDelete($cascade)
	{
		$path = $this->getPath('*');
		if (!$path) {
			return true;
		}

                $glob = WWW_ROOT . DS . 'img' . DS . 'products' . DS . $path;
		$files = glob($glob);

		foreach ($files as $file) {
			@unlink($file);
		}

		return true;
	}
}

?>
