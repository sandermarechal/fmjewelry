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
 * The Product Model
 */
class Product extends AppModel
{
	/** @var array The model behaviors */
	public $actsAs = array(
		'Containable',
		'Sluggable' => array('label' => 'name', 'overwrite' => true)
	);

	/** @var array Products belong to many categories and can have many different components */
	public $hasAndBelongsToMany = array('Category' => array('with' => 'CategoriesProduct'));

        /** @var array Product belongs to User */
        public $belongsTo = array('User');

	/** @var array Product validation rules */
	public $validate = array(
		'name' => 'notEmpty',
		'lead' => 'notEmpty',
		'description' => 'notEmpty',
		'price' => array(
			'type'   => array('rule' => array('decimal', 2)),
			'length' => array('rule' => array('between', 4, 9)),
		),
	);
}

?>
