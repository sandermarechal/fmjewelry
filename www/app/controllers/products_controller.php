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
	public $helpers = array('Html', 'Form', 'Header', 'Button', 'Javascript');

	/** @var array The components for this controller */
	public $components = array('Auth');

        /* @var array Models to use */
        public $uses = array('ImagesProduct', 'Product');

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
		$this->Product->contain(array('Category'));
		$product = $this->Product->findBySlug($slug);
		$this->set(compact('product'));
	}

	public function admin_index()
	{
                $this->paginate = array('Product' => array(
                        'contain' => array('Category', 'User'),
                ));

                $products = $this->paginate('Product');
		$this->set(compact('products'));
	}

	public function admin_view($id = null)
	{
		if (!$id) {
			$this->Session->setFlash(__('Invalid Product.', true));
			$this->redirect(array('action'=>'index'));
		}

                $this->Product->contain(array(
                        'Category',
                        'Image' => array('order' => 'ImagesProduct.order ASC'),
                        'User'
                ));

		$product = $this->Product->read(null, $id);
                foreach ($product['Image'] as &$image) {
                        $this->Product->Image->id = $image['id'];
                        $this->Product->Image->thumb(170);
                        $image['thumb'] = '/img/products/' . $this->Product->Image->getPath(170);
                }
                
                $ids = Set::extract('/Image/id', $product);
                $images = $this->Product->Image->find('list', array(
                        'conditions' => array(
                                'user_id' => $this->Auth->user('id'),
                                'not' => array('id' => $ids),
                        ),
                        'recursive' => -1,
                ));

		$this->set(compact('product', 'images'));
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

		$this->set(compact('categories', 'images'));
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
		$this->set(compact('categories','images'));
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

        public function admin_attach()
        {
                if (empty($this->data)) {
			$this->redirect(array('action'=>'index'));
                }

                $data = $this->data['ImagesProduct'];
                if (!$this->_checkAccess($data['product_id'])) {
                        $this->Session->setFlash(__('You are not allowed to edit that product', true));
                        $this->redirect(array('action' => 'view', $data['product_id']));
                }

                $this->ImagesProduct->create();
                $this->ImagesProduct->save($data);

                $this->Session->setFlash(__('The image has been added', true));
                $this->redirect(array('action' => 'view', $data['product_id']));
        }

        public function admin_detach($id)
        {
                $link = $this->ImagesProduct->read(null, $id);
                if (!$this->_checkAccess($link['ImagesProduct']['product_id'])) {
                        $this->Session->setFlash(__('You are not allowed to edit that product', true));
                } else {
                        $this->Session->setFlash(__('The image has been removed from this product', true));
                        $this->ImagesProduct->delete($id);
                }

                $this->redirect(array('action' => 'view', $link['ImagesProduct']['product_id']));
        }

        public function admin_moveup($id)
        {
                $link = $this->ImagesProduct->read(null, $id);
                if (!$this->_checkAccess($link['ImagesProduct']['product_id'])) {
                        $this->Session->setFlash(__('You are not allowed to edit that product', true));
                } else {
                        $this->ImagesProduct->moveup($id);
                }

                $this->redirect(array('action' => 'view', $link['ImagesProduct']['product_id']));
        }

        public function admin_movedown($id)
        {
                $link = $this->ImagesProduct->read(null, $id);
                if (!$this->_checkAccess($link['ImagesProduct']['product_id'])) {
                        $this->Session->setFlash(__('You are not allowed to edit that product', true));
                } else {
                        $this->ImagesProduct->movedown($id);
                }

                $this->redirect(array('action' => 'view', $link['ImagesProduct']['product_id']));
        }
}

?>
