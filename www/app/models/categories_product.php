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
 * The CategoriesProduct relationship model
 */
class CategoriesProduct extends AppModel
{
	/** @var array Allow products to be ordered per category */
	public $actsAs = array('Ordered' => array(
		'field' => 'order',
		'foreign_key' => 'category_id',
	));

	/** @var array The models this many-to-many relationship connects */
	public $belongsTo = array(
		'Category',
		'Product',
	);
}

?>
