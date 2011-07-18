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
 * Controller for the Groups Address model
 *
 * This controller allows users and admins to maintain addresses
 */
class AddressesController extends AppController
{
	/** @var array The view helpers */
	public $helpers = array('Html', 'Form', 'Button', 'Javascript');

	/** @var array The components for this controller */
	public $components = array('Auth');

	/**
	 * Make sure an Order session variable exists to store shipping and billing addresses
	 * @return void
	 */
	public function beforeFilter()
	{
		parent::beforeFilter();
		if (!is_array($this->Session->read('Order'))) {
			$this->Session->write('Order', array('Order' => array()));
		}
	}

	/**
	 * Check address access
	 * @param string $id The address ID
	 * @return mixed The address on success, false otherwise
	 */
	private function _get($id = null)
	{
		if (!$id && empty($this->data)) {
			return false;
		}

		$address = $this->Address->read(null, $id);
		if ($address['Address']['user_id'] != $this->Auth->user('id')) {
			return false;
		}

		return $address;
	}

	/**
	 * List all a user's addresses
	 */
	public function index()
	{
		$this->Address->recursive = 0;
		$addresses = $this->Address->find('all',  array(
			'conditions' => array(
				'Address.user_id' => $this->Auth->user('id'),
				'Address.deleted' => 0,
			),
			'order' => array(
				'Address.primary DESC',
				'Address.name ASC',
			)
		));
		$this->set('addresses', $addresses);
	}

	/**
	 * Add a new address
	 */
	public function add()
	{
		if (!empty($this->data)) {
			$this->data['Address']['user_id'] = $this->Auth->user('id');

			$this->Address->create();
			if ($this->Address->save($this->data)) {
				$this->Session->setFlash(__('The address has been saved.', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The address could not be saved.', true));
			}
		}

		$this->render('edit');
	}

	/**
	 * Edit an address
	 * @param string $id The address ID
	 */
	public function edit($id = null)
	{
		if (!$address = $this->_get($id)) {
			$this->flash(__('Invalid address', true), array('action'=>'index'));
		}

		if (!empty($this->data)) {
			if ($this->Address->save($this->data)) {
				$this->Session->setFlash(__('The address has been saved.', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The address could not be saved.', true));
			}
		} else {
			$this->data = $address;
		}
	}

	/**
	 * Delete an address
	 * @param string $id The address ID
	 */
	public function delete($id = null)
	{
		if (!$this->_get($id)) {
			$this->Session->setFlash(__('Invalid address', true));
			$this->redirect(array('action' => 'index'));
		}

		$this->Address->delete($id);
		$this->Session->setFlash(__('The address has been deleted.', true));
		$this->redirect(array('action' => 'index'));
	}

	/**
	 * Select an existing address or add a new one
	 * @param string $type The address type. Should be 'shipping' or 'billing'.
	 */
	public function select($type = 'shipping')
	{
		if ($type != 'shipping' && $type != 'billing') {
			$this->flash('Something went wrong...', array('controller' => 'cart', 'action' => 'index'));
		}

		if (!empty($this->data)) {
			if (isset($this->data['address_id'])) {
				$address_id = $this->data['address_id'];
			} else {
				$address_id = false;
			}

			if (!$address_id || $address_id == 'address_new') {
				$this->data['Address']['user_id'] = $this->Auth->user('id');

				$this->Address->create();
				if ($this->Address->save($this->data)) {
					$address_id = $this->Address->id;
				} else {				
					$address_id = false;
					$this->Session->setFlash(__('The address could not be saved.' . $address_id, true));
				}
			}

			if ($address_id) {
				$this->Session->write('Order.Order.' . $type . '_address_id', $address_id);
				$this->redirect(array('controller' => 'cart', 'action' => 'checkout'));
			}
		}

		// The addresses to select from
		$this->Address->recursive = 0;
		$addresses = $this->Address->find('all',  array(
			'conditions' => array(
				'Address.user_id' => $this->Auth->user('id'),
				'Address.deleted' => 0,
			),
			'order' => array(
				'Address.primary DESC',
				'Address.name ASC',
			)
		));

		$emailAddress = $this->Auth->user('email_address');
		$this->set(compact('type', 'addresses', 'emailAddress'));
	}

	/**
	 * Set the address as primary
	 * @param string $id The address ID
	 */
	public function primary($id = null)
	{
		if (!$this->_get($id)) {
			$this->Session->setFlash(__('Invalid address', true));
			$this->redirect(array('action' => 'index'));
		}

		if (!$this->Address->setPrimary($id)) {
			$this->Session->setFlash(__('The address could not be set as primary address.', true));
			$this->redirect(array('action' => 'index'));
		}

		$this->redirect(array('action' => 'index'));
	}

	/**
	 * Show all addresses for a certain user
	 * @param string $user_id The user ID
	 */
	public function admin_index($user_id = null)
	{
		if (!$user_id) {
			$this->Session->setFlash(__('Invalid user', true));
			$this->redirect(array('controller' => 'users', 'action'=>'index'));
		}

		$this->Address->recursive = 0;
		$addresses = $this->Address->find('all',  array(
			'conditions' => array(
				'Address.user_id' => $user_id,
				'Address.deleted' => 0,
			),
			'order' => array(
				'Address.primary DESC',
				'Address.name ASC',
			)
		));

		$user = $this->Address->User->read(0, $user_id);
		$this->set(compact('addresses', 'user'));
		$this->render('index');
	}

	/**
	 * Add a new address
	 * @param string $user_id The user to add the address to
	 */
	public function admin_add($user_id = null)
	{
		if (!$user_id && empty($this->data)) {
			$this->flash(__('Invalid address', true), array('controller' => 'users', 'action'=>'index'));
		}

		if (!empty($this->data)) {
			$this->Address->create();
			if ($this->Address->save($this->data)) {
				$this->Session->setFlash(__('The address has been saved.', true));
				$this->redirect(array('action' => 'index', $this->data['Address']['user_id']));
			} else {
				$this->Session->setFlash(__('The address could not be saved.', true));
			}
		}

		$this->data['Address']['user_id'] = $user_id;
		$users = $this->Address->User->find('list');
		$this->set(compact('users'));
		$this->render('edit');
	}

	/**
	 * Edit an address
	 * @param string $id The address ID
	 */
	public function admin_edit($id = null)
	{
		if (!$id && empty($this->data)) {
			$this->flash(__('Invalid address', true), array('controller' => 'users', 'action'=>'index'));
		}

		if (!empty($this->data)) {
			if ($this->Address->save($this->data)) {
				$this->Session->setFlash(__('The address has been saved.', true));
				$this->redirect(array('action' => 'index', $this->data['Address']['user_id']));
			} else {
				$this->Session->setFlash(__('The address could not be saved.', true));
			}
		} else {
			$this->data = $this->Address->read(null, $id);
		}

		$users = $this->Address->User->find('list');
		$this->set(compact('users'));
		$this->render('edit');
	}

	/**
	 * Delete an address
	 * @param string $id The address ID
	 */
	public function admin_delete($id = null)
	{
		if (!$id) {
			$this->flash(__('Invalid address', true), array('controller' => 'users', 'action'=>'index'));
		}

		$address = $this->Address->read(null, $id);

		$this->Address->delete($id);
		$this->Session->setFlash(__('Address deleted', true));
		$this->redirect(array('action' => 'index', $address['Address']['user_id']));
	}

	/**
	 * Set the address as primary
	 * @param string $id The address ID
	 */
	public function admin_primary($id = null)
	{
		if (!$this->_get($id)) {
			$this->Session->setFlash(__('Invalid address', true));
			$this->redirect(array('action' => 'index'));
		}

		$address = $this->Address->read(null, $id);
		$this->Address->setPrimary($id);
		$this->redirect(array('action' => 'index', $address['Address']['user_id']));
	}
}

?>
