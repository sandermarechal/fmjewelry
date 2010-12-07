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
 * The Part Model
 */
class Part extends AppModel
{
	/** @var array The model behaviors */
	public $actsAs = array('Containable');

	/** @ array A Part can have many options */
	public $hasMany = array('PartOption');

	/** @var array Parts can belong to many different products */
	public $hasAndBelongsToMany = array('Product');

	/** @var array Part validation rules */
	public $validate = array(
		'name' => 'notEmpty',
		'label' => 'notEmpty',
	);
}

?>
