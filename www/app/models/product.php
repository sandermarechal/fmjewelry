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
	public $hasAndBelongsToMany = array('Part', 'Category' => array('with' => 'CategoriesProduct'));

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

	/**
	 * Get the total price for a product based on PartOption filters
	 * @param string $id The product ID
	 * @param array $contain A containable snippet to filter the PartOptions
	 */
	private function _getPrice($id = null, $contain = array())
	{
		if (!$id) {
			$id = $this->id;
		}

		$product = $this->find('first', array(
			'contain' => array(
				'Part.id',
				'Part.PartOption' => $contain,
			),
			'conditions' => array(
				'Product.id' => $id,
			),
		));

		$price = $product['Product']['price'];
		foreach ($product['Part'] as $part) {
			if ($part['PartOption']) {
				$price = bcadd($price, $part['PartOption'][0]['price'], 2);
			}
		}

		return $price;
	}

	/**
	 * Get the default price for a product, including all options.
	 * @param string $id The product ID
	 */
	public function getDefaultPrice($id = null)
	{
		return $this->_getPrice($id, array(
			'conditions' => array('PartOption.default' => true),
		));
	}

	/**
	 * Get the minimum price for a product, including all options.
	 * @param string $id The product ID
	 */
	public function getMinimumPrice($id = null)
	{
		return $this->_getPrice($id, array(
			'order' => 'PartOption.price ASC',
		));
	}
}

?>
