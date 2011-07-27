<?php
/**
 * Full Metal Jewelry website
 * Copyright (C) 2011 Stichting Lone Wolves
 * Written by Sander Marechal <s.marechal@jejik.com>
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 */

/** @var int Defines infinite stock (make-to-order) **/
define('STOCK_INFINITE', -1);

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
        public $hasAndBelongsToMany = array(
                'Category' => array('with' => 'CategoriesProduct'),
                'Image' => array('with' => 'ImagesProduct'),
        );

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
                'stock' => array(
                        'type' => array('rule' => 'numeric'),
                        'range' => array('rule' => array('range', -2, 1000)),
                )
	);

	/**
	 * Convert the Markdown description to HTML before saving
	 * @return boolean True to continue saving
	 */
	public function beforeSave()
	{
		if (isset($this->data['Product']['description'])) {
			App::import('Vendor', 'markdown');
			$this->data['Product']['description_html'] = Markdown($this->data['Product']['description']);
		}

		return true;
	}
}

?>
