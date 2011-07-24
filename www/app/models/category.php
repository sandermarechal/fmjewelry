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
 * The Category Model
 */
class Category extends AppModel
{
	/** @var array The model behaviors */
	public $actsAs = array(
		'Containable',
		'Ordered' => array('field' => 'order', 'foreign_key' => false),
		'Sluggable' => array('label' => 'name', 'overwrite' => true),
	);

	/** @var array Many-to-many relations */
	public $hasAndBelongsToMany = array('Product' => array('with' => 'CategoriesProduct'));

	/** @var array The validation rules */
	public $validate = array(
		'name' => 'notEmpty',
		'slug' => 'notEmpty',
	);

	/**
	 * Set the category as the root category
	 * 
	 * @param string $id The category ID
	 * @return boolean Success
	 */
	public function setRoot($id = null)
	{
		if ($id == null) {
			$id = $this->id;
		}

		$category = $this->find('first', array('conditions' => array(
			'Category.id' => $id
		)));

		if (!$category) {
			return false;
		}

		$this->updateAll(array('Category.root' => 0));

		$category['Category']['root'] = 1;
		return (bool) $this->save($category);
	}

	/**
	 * Get the subcategories
	 */
	public function getSubcategories()
	{
		return $this->find('all', array(
			'conditions' => array('Category.root' => 0),
		));
	}

	/**
	 * Get all the products in the current category
	 * @param string $id The category ID
	 */
	public function getProducts($id = null)
	{
		if (!$id) {
			$id = $this->id;
		}

		$category = $this->find('first', array(
			'contain' => array(
				'Product' => array('order' => 'CategoriesProduct.order ASC'),
                'Product.Image',
			),
			'conditions' => array('Category.id' => $id),
		));

		$products = array();
		foreach ($category['Product'] as $product) {
			unset($product['CategoriesProduct']);
            if (count($product['Image'])) {
                $this->Product->Image->id = $product['Image'][0]['id'];
                $this->Product->Image->thumb(150);
                $product['image'] = $this->Product->Image->getPath(150);
                unset($product['Image']);
            }
			$products[] = array('Product' => $product);
		}

		return $products;
	}
}

?>
