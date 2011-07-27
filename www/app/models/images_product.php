<?php
/**
 * Full Metal Jewelry website
 * Copyright (C) 2011 Stichting Lone Wolves
 * Written by Sander Marechal <s.marechal@jejik.com>
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 */

/**
 * The ImagesProduct relationship model
 */
class ImagesProduct extends AppModel
{
	/** @var array Allow images to be ordered per product */
	public $actsAs = array('Ordered' => array(
		'field' => 'order',
		'foreign_key' => 'product_id',
	));

	/** @var array The models this many-to-many relationship connects */
	public $belongsTo = array(
		'Image',
		'Product',
	);
}

?>
