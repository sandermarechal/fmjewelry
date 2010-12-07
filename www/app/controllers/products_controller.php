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
 * The Products controller
 */
class ProductsController extends AppController
{
	/** @var array The view helpers */
	var $helpers = array('Html', 'Form', 'Header', 'Button', 'Javascript');

	/** @var array The components for this controller */
	var $components = array('Auth');

	/**
	 * Set the auth permissions for this controller
	 * @return void
	 */
	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->Auth->allow('index', 'view');
	}

	public function index()
	{
		$this->redirect('/');
	}

	public function view($slug = null)
	{
		if (!$slug) {
			$this->Session->setFlash(__('Invalid Product.', true));
			$this->redirect(array('controller' => 'categories', 'action' => 'index'));
		}

		// Get the product
		$this->Product->contain(array('Category', 'Part', 'Part.PartOption'));
		$product = $this->Product->findBySlug($slug);
		$product['Product']['price_default'] = $this->Product->getDefaultPrice($product['Product']['id']);
		$product['Product']['price_min'] = $this->Product->getMinimumPrice($product['Product']['id']);

		// List all prices
		$prices = array();
		foreach ($product['Part'] as $part) {
			foreach ($part['PartOption'] as $option) {
				$prices[$option['id']] = (float) $option['price'];
			}
		}

		$this->set(compact('product', 'prices'));
	}

	public function admin_index()
	{
		$this->Product->recursive = 0;
		$this->set('products', $this->paginate());
	}

	public function admin_view($id = null)
	{
		if (!$id) {
			$this->Session->setFlash(__('Invalid Product.', true));
			$this->redirect(array('action'=>'index'));
		}

		$this->Product->contain(array('Category', 'Part', 'Part.PartOption', 'User'));
		$product = $this->Product->read(null, $id);
		$product['Product']['price_default'] = $this->Product->getDefaultPrice($id);
		$product['Product']['price_min'] = $this->Product->getMinimumPrice($id);

		$this->set('product', $product);
	}

	private function _getImages()
	{
		//TODO: Fix this when photo uploading has been ported
		return array();

		$images = glob(WWW_ROOT . 'img/products/*.*');
		foreach ($images as &$image) {
			$image = basename($image);
		}

		return array_combine($images, $images);
	}

	public function admin_add()
	{
		if (!empty($this->data)) {
			$this->Product->create();
                        $this->data['Product']['user_id'] = $this->Auth->user('id');
			if ($this->Product->save($this->data)) {
				$this->Session->setFlash(__('The Product has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The Product could not be saved. Please, try again.', true));
			}
		}

		$categories = $this->Product->Category->find('list');
		$images = $this->_getImages();
		$parts = $this->Product->Part->find('list');

		$this->set(compact('categories', 'images', 'parts'));
		$this->render('admin_edit');
	}

        private function _checkAccess($id)
        {
                $this->Product->id = $id;
                $user_id = $this->Product->field('user_id');
                return ($this->Auth->user('id') == $user_id);
        }

	public function admin_edit($id = null)
	{
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Product', true));
			$this->redirect(array('action'=>'index'));
		}

		if (!empty($this->data)) {
                        if (!$this->_checkAccess($this->data['Product']['id'])) {
				$this->Session->setFlash(__('You are not allowed to edit that product', true));
				$this->redirect(array('action'=>'index'));
                        }

			if ($this->Product->save($this->data)) {
				$this->Session->setFlash(__('The Product has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The Product could not be saved. Please, try again.', true));
			}
		}

		if (empty($this->data)) {
                        if (!$this->_checkAccess($id)) {
                                $this->Session->setFlash(__('You are not allowed to edit that product', true));
                                $this->redirect(array('action'=>'index'));
                        }

			$this->data = $this->Product->read(null, $id);
		}

		$categories = $this->Product->Category->find('list');
		$images = $this->_getImages();
		$parts = $this->Product->Part->find('list');

		$this->set(compact('categories','images', 'parts'));
	}

	public function admin_delete($id = null)
	{
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for Product', true));
			$this->redirect(array('action'=>'index'));
		}

                if (!$this->_checkAccess($id)) {
                        $this->Session->setFlash(__('You are not allowed to delete that product', true));
                        $this->redirect(array('action'=>'index'));
                }

		if ($this->Product->del($id)) {
			$this->Session->setFlash(__('Product deleted', true));
			$this->redirect(array('action'=>'index'));
		}
	}
}

?>
