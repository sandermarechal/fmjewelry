<?php

/**
 * Full Metal Jewelry website
 * Copyright (C) 2009 Stichting Lone Wolves
 * Written by Sander Marechal <s.marechal@jejik.com>
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 */

/**
 * The Categories Controller
 */
class CategoriesController extends AppController
{
	/** @var array The components this controller uses */
	public $components = array('Auth');
	
	/** @var array The helpers for the controller */
	public $helpers = array('Html', 'Form', 'Header', 'Button');

	/**
	 * Set the auth permissions for this controller
	 * @return void
	 */
	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->Auth->allow('index', 'view');
	}

	/**
	 * Redirect index to root
	 */
	public function index()
	{
		$this->Category->contain();
		$category =  $this->Category->findByRoot(1);
		if (!$category) {
			$this->Session->setFlash(__('No root category set!', true));
			$this->cakeError('error404');
		}

		$subcategories = $this->Category->getSubcategories($category['Category']['id']);
		$products = $this->Category->getProducts($category['Category']['id']);

		$this->set(compact('category', 'subcategories', 'products'));
		$this->render('view');
	}

	/**
	 * View a category
	 * @param string $slug The category slug
	 */
	public function view($slug = null)
	{
		if (!$slug) {
			$this->Session->setFlash(__('Invalid category.', true));
			$this->redirect('/');
		}

		$this->Category->contain();
		$category =  $this->Category->findBySlug($slug);
		if (!$category) {
			$this->Session->setFlash(__('Invalid category.', true));
			$this->redirect('/');
		}

		$subcategories = $this->Category->getSubcategories($category['Category']['id']);
		$products = $this->Category->getProducts($category['Category']['id']);

		$this->set(compact('category', 'subcategories', 'products'));
	}

	/**
	 * List all categories
	 */
	public function admin_index()
	{
		$this->Category->recursive = 0;
		$this->set('categories', $this->paginate());
	}

	/**
	 * View a single category
	 * @param string $slug The category slug
	 */
	public function admin_view($slug = null)
	{
		if (!$slug) {
			$this->Session->setFlash(__('Invalid category.', true));
			$this->redirect(array('action' => 'index'));
		}

		$category = $this->Category->find('first', array(
			'contain' => array(
				'Product' => array(
					'order' => 'CategoriesProduct.order ASC',
				),
				'Product.CategoriesProduct',
			),
			'conditions' => array('Category.slug' => $slug),
		));

		$this->set('category', $category);
	}

	private function _getImages()
	{
		// TODO: Update this when image uploading has been poretd
		return array();

		$images = glob(WWW_ROOT . 'img/categories/*.*');
		foreach ($images as &$image) {
			$image = basename($image);
		}

		return array_combine($images, $images);
	}

	/**
	 * Add a new category
	 */
	public function admin_add()
	{
		if (!empty($this->data)) {
			$this->Category->create();
			if ($this->Category->save($this->data)) {
				$this->Session->setFlash(__('The category has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The category could not be saved. Please, try again.', true));
			}
		}

		$this->set('images', $this->_getImages());
		$this->render('admin_edit');
	}

	/**
	 * Edit a category
	 * @param string $id The category ID
	 */
	public function admin_edit($id = null)
	{
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid category', true));
			$this->redirect(array('action' => 'index'));
		}

		if (!empty($this->data)) {
			if ($this->Category->save($this->data)) {
				$this->Session->setFlash(__('The category has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The category could not be saved. Please, try again.', true));
			}
		} else {
			$this->data = $this->Category->read(null, $id);
		}

		$this->set('images', $this->_getImages());
	}

	/**
	 * Check that $id is set. Redirect with an error if not.
	 * @param string $id The category ID
	 */
	private function _checkID($id = null)
	{
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for category', true));
			$this->redirect(array('action'=>'index'));
		}
	}

	/**
	 * Delete a category
	 * @param string $id The category ID
	 */
	public function admin_delete($id = null)
       	{
		$this->_checkID($id);
		if ($this->Category->delete($id)) {
			$this->Session->setFlash(__('Category deleted', true));
			$this->redirect(array('action'=>'index'));
		}
	}

	/**
	 * Set a category as the root category
	 * @param string $id The category ID
	 */
	public function admin_set_root($id = null)
       	{
		$this->_checkID($id);
		if (!$this->Category->setRoot($id)) {
			$this->Session->setFlash(__('Category could not be set as root.', true));
		}
		
		$this->redirect(array('action' => 'index'));
	}

	/**
	 * Move a category up
	 * @param string $id The category ID
	 */
	public function admin_moveup($id = null)
       	{
		$this->_checkID($id);
		if (!$this->Category->moveup($id)) {
			$this->Session->setFlash(__('Category could not be moved.', true));
		}
		
		$this->redirect(array('action' => 'index'));
	}

	/**
	 * Move a category up
	 * @param string $id The category ID
	 */
	public function admin_movedown($id = null)
       	{
		$this->_checkID($id);
		if (!$this->Category->movedown($id)) {
			$this->Session->setFlash(__('Category could not be moved.', true));
		}
		
		$this->redirect(array('action' => 'index'));
	}

	/**
	 * Fix order conflicts
	 */
	public function admin_reset_order()
	{
		$this->Category->resetweights();
		$this->redirect(array('action' => 'index'));
	}

	/**
	 * Move product up inside a category
	 * @param string $id CategoriesProduct ID
	 */
	public function admin_product_moveup($id = null)
	{
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for category-product link', true));
			$this->redirect(array('action'=>'index'));
		}

		if (!$this->Category->CategoriesProduct->moveup($id)) {
			$this->Session->setFlash(__('Relationship could not be moved.', true));
		}
		
		$link = $this->Category->CategoriesProduct->read(null, $id);
		$this->redirect(array('action' => 'view', $link['Category']['slug']));
	}

	/**
	 * Move product up inside a category
	 * @param string $id CategoriesProduct ID
	 */
	public function admin_product_movedown($id = null)
	{
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for category-product link', true));
			$this->redirect(array('action'=>'index'));
		}

		if (!$this->Category->CategoriesProduct->movedown($id)) {
			$this->Session->setFlash(__('Relationship could not be moved.', true));
		}
		
		$link = $this->Category->CategoriesProduct->read(null, $id);
		$this->redirect(array('action' => 'view', $link['Category']['slug']));
	}

	/**
	 * Fix order conflicts on products
	 * @param string $slug The category slug
	 */
	public function admin_product_reset_order($slug = null)
	{
		$this->Category->CategoriesProduct->resetweights();
		$this->redirect(array('action' => 'view', $slug));
	}
}

?>
