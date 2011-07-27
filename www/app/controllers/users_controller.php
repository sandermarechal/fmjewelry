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
 * Controller for the User model
 */
class UsersController extends AppController
{
	/** @var array The components this controller uses */
	public $components = array('Email', 'Auth');
	
	/** @var array The helpers that will be available on the view */
	public $helpers = array('Html', 'Form', 'Button', 'Javascript');

	/**
	 * Set the auth permissions for this controller
	 * @return void
	 */
	function beforeFilter()
	{
		parent::beforeFilter();
		$this->Auth->allow('index', 'register', 'login', 'logout', 'recover', 'reset', 'activate');
	}

	/**
	 * Login for the user. Empty because it is automagically handled by the Auth class.
	 * @return void
	 */
	public function login() {}

	/**
	 * Remove session permission data and logout
	 * @return void
	 */
	public function logout()
	{
		$redirect = $this->Auth->logout();
		$this->Session->destroy();
		$this->redirect($redirect);
	}

	/**
	 * Display a login/register page or redirect to view() for logged in users
	 * @return void
	 */
	public function index()
	{
		if ($this->Auth->user('id')) {
			$this->redirect(array('action' => 'view'));
		}

		$this->redirect(array('action' => 'login'));
	}

	/**
	 * Display the user's own account page
	 * @return void
	 */
	public function view()
	{
		$this->set(array(
			'user' => $this->User->read(null, $this->Auth->user('id')),
		));
	}

	/**
	 * Register for a new account
	 * @return void
	 */
	public function register()
	{
		if (empty($this->data)) {
			return;
		}

		if ($this->User->find('first', array('conditions' => array('User.email_address' => $this->data['User']['email_address'])))) {
			$this->Session->setFlash(__('An account already exists with that e-mail address.', true));
			return;
		}

		$this->User->create();
		if ($this->User->save($this->data)) {
			$this->_sendActivationEmail($this->User->getLastInsertID());
			$this->Session->setFlash(__('A registration verification e-mail has been sent. After your account has been activated you can login.', true));
			$this->redirect(array('action' => 'login'));
		} else {
			$this->Session->setFlash(__('The account could not be registered. Please, try again.', true));
		}
	}

	/**
	 * Account activation from the link in the e-mail
	 *
	 * @param string $id The user ID to activate
	 * @param string $hash The user activation hash
	 * @return void
	 */
	public function activate($id = null, $hash = null)
	{
		if ($id && $hash) {
			$this->User->id = $id;
			if ($this->User->activate($hash)) {
				$this->Session->setFlash(__('Your account has been activated. You can now login.', true));
				$this->redirect(array('action' => 'login'));
			}
		}

		$this->Session->setFlash(__('Your account was *not* activated. Please, try again or re-register.', true));
		$this->redirect(array('action' => 'register'));
	}

	/**
	 * Send an activation e-mail to the specified user.
	 *
	 * @param string $id The ID of the user to send the e-mail to
	 * @return boolean
	 */
	private function _sendActivationEmail($id = null)
	{
		if (!$id) {
			return false;
		}

		$this->User->id = $id;
		$this->User->read();

		$this->set('username', $this->User->field('name'));
		$this->set('activation_url', Router::url(array(
			'controller' => 'users',
			'action' => 'activate',
			$this->User->field('id'),
			$this->User->getActivationHash()
		), true));
		$this->set('base_url', Router::url('/', true));

		$this->Email->to = $this->User->field('email_address');
		$this->Email->subject = __('[Full Metal Jewelry] Please confirm your registration', true);
		$this->Email->from = Configure::read('Email.admin');
		$this->Email->template = 'activate';
		$this->Email->sendAs = 'text';
		$this->Email->delivery = 'mail'; // debug or mail
		return $this->Email->send();
	}

	/**
	 * Password recovery form
	 */
	public function recover()
	{
		if ($this->Auth->user('id')) {
			$this->redirect(array('action' => 'edit'));
		}

		if (!empty($this->data)) {
			$user = $this->User->find('first', array('conditions' => array('User.email_address' => $this->data['User']['email_address'])));
			if (isset($user['User']['id'])) {
				$this->_sendRecoveryEmail($user['User']['id']);
				$this->Session->setFlash(__('An e-mail has been sent containing instructions to reset your password.', true));
				return;
			}

			$this->Session->setFlash(__('That e-mail address does not belong to a registered user.', true));
		}
	}

	/**
	 * Send a password recovery e-mail to the specified user.
	 *
	 * @param string $id The ID of the user to send the e-mail to
	 * @return boolean
	 */
	private function _sendRecoveryEmail($id = null)
	{
		if (!$id) {
			return false;
		}

		$this->User->id = $id;
		$this->User->read();

		$this->set('username', $this->User->field('name'));
		$this->set('reset_url', Router::url(array(
			'controller' => 'users',
			'action' => 'reset',
			$this->User->field('id'),
			$this->User->getRecoveryHash()
		), true));
		$this->set('base_url', Router::url('/', true));

		$this->Email->to = $this->User->field('email_address');
		$this->Email->subject = __('[Full Metal Jewelry] Your password recovery request', true);
		$this->Email->from = Configure::read('Email.admin');
		$this->Email->template = 'recover';
		$this->Email->sendAs = 'text';
		$this->Email->delivery = 'mail'; // debug or mail
		return $this->Email->send();
	}

	/**
	 * Reset a user's password from the e-mail link in the password recovery e-mail
	 *
	 * @param string $id The user ID
	 * @param string $hash The user password recovery hash
	 * @return void
	 */
	public function reset($id = null, $hash = null)
	{
		if (!empty($this->data)) {
			$this->User->id = $this->data['User']['id'];
			if ($this->User->getRecoveryHash() == $this->data['User']['hash']) {

				// Check the new passwords match
				if (empty($this->data['User']['new_password']) || $this->data['User']['new_password'] !== $this->data['User']['new_password_confirm']) {
					$this->Session->setFlash(__('Your new password did not match the confirmation', true));
					$this->set('hash', $this->data['User']['hash']);
					return;
				}

				$this->data['User']['password'] = $this->Auth->password($this->data['User']['new_password']);

				if ($this->User->save($this->data)) {
					$this->Session->setFlash(__('Your password has been updated. You can now log in.', true));
					$this->Session->write('Auth.redirect', '/');
					$this->redirect(array('action' => 'login'));
				} else {
					$this->Session->setFlash(__('Your changes could not be saved. Please, try again.', true));
					$this->redirect(array('action' => 'recover'));
				}
			}
		}

		if ($id && $hash) {
			$this->User->id = $id;
			if ($this->User->getRecoveryHash() == $hash) {
				$this->data = $this->User->read(null, $id);
				$this->set('hash', $hash);
				return;
			}
		}

		$this->Session->setFlash(__('Invalid password recovery URL.', true));
		$this->redirect(array('action' => 'recover'));
	}

	/**
	 * A user can edit his own account information
	 *
	 * @return void
	 */
	public function edit()
	{
		$this->User->id = $this->Auth->user('id');
		
		if (empty($this->data)) {
			$this->data = $this->User->read();
            $mailer = in_array('Mailers', Set::extract('/Group/name', $this->data));
            $this->set(compact('mailer'));
			return;
		}

		// Check the new passwords match
		if ($this->data['User']['new_password'] || $this->data['User']['new_password_confirm']) {
			if ($this->data['User']['new_password'] !== $this->data['User']['new_password_confirm']) {
				$this->Session->setFlash(__('Your new password did not match the confirmation', true));
				return;
			}

			$this->data['User']['password'] = $this->Auth->password($this->data['User']['new_password']);
		}

		// When the e-mail address changes, make sure it doesn't exist yet
		if ($this->data['User']['email_address'] !== $this->Auth->user('email_address')) {
			if ($this->User->find('first', array('conditions' => array('User.email_address' => $this->data['User']['email_address'])))) {
				$this->Session->setFlash(__('An different account already exists with that e-mail address.', true));
				return;
			}
		}

		// Save the User
		if ($this->User->save($this->data)) {
			$this->Session->setFlash(__('Your changes has been saved', true));
			$this->redirect(array('action' => 'view'));
		} else {
			$this->Session->setFlash(__('Your changes could not be saved. Please, try again.', true));
		}
	}

	/**
	 * List all user accounts
	 * @return void
	 */
	public function admin_index()
	{
		$this->paginate = array(
			'contain' => array('Group'),
			'order' => array('User.name' => 'asc'),
		);
		$this->set('users', $this->paginate());
	}

	/**
	 * View all information about a user
	 *
	 * @param string $id The ID of the user
	 * @return void
	 */
	public function admin_view($id = null)
	{
		if (!$id) {
			$this->Session->setFlash(__('Invalid User.', true));
			$this->redirect(array('action'=>'index'));
		}

		$this->set(array(
			'user' => $this->User->read(null, $id),
		));

		$this->render('view');
	}

	/**
	 * Add a new user
	 *
	 * @return void
	 */
	public function admin_add()
	{
		$groups = $this->User->Group->find('list');
		$this->set(compact('groups'));

		if (!empty($this->data)) {
			// Check the new passwords match
			if (empty($this->data['User']['new_password']) || empty($this->data['User']['new_password_confirm'])) {
				$this->Session->setFlash(__('The password or confirmation is empty', true));
				return;
			}

			if ($this->data['User']['new_password'] !== $this->data['User']['new_password_confirm']) {
				$this->Session->setFlash(__('The password did not match the confirmation', true));
				return;
			}

			$this->data['User']['password'] = $this->Auth->password($this->data['User']['new_password']);

			// When the e-mail address changes, make sure it doesn't exist yet
			if ($this->User->find('first', array('conditions' => array('User.email_address' => $this->data['User']['email_address'])))) {
				$this->Session->setFlash(__('An different account already exists with that e-mail address.', true));
				return;
			}

			if ($this->User->save($this->data)) {
				$this->Session->setFlash(__('The User has been saved', true));
				$this->redirect(array('action'=>'view', $this->User->id));
			} else {
				$this->Session->setFlash(__('The User could not be saved. Please, try again.', true));
			}
		}

		$this->render('admin_edit');
	}

	/**
	 * Edit any user
	 *
	 * @param string $id The ID of the user
	 * @return void
	 */
	public function admin_edit($id = null)
	{
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid User', true));
			$this->redirect(array('action'=>'index'));
		}
		
		$groups = $this->User->Group->find('list');
		$this->set(compact('groups'));

		// Check the new passwords match
		if ($this->data['User']['new_password'] || $this->data['User']['new_password_confirm']) {
			if ($this->data['User']['new_password'] !== $this->data['User']['new_password_confirm']) {
				$this->Session->setFlash(__('The new password did not match the confirmation', true));
				return;
			}

			$this->data['User']['password'] = $this->Auth->password($this->data['User']['new_password']);
		}

		// When the e-mail address changes, make sure it doesn't exist yet
		$this->User->id = $id;
		if ($this->data['User']['email_address'] !== $this->User->field('email_address')) {
			if ($this->User->find('first', array('conditions' => array('User.email_address' => $this->data['User']['email_address'])))) {
				$this->Session->setFlash(__('An different account already exists with that e-mail address.', true));
				return;
			}
		}

		if (!empty($this->data)) {
			if ($this->User->save($this->data)) {
				$this->Session->setFlash(__('The User has been saved', true));
				$this->redirect(array('action'=>'view', $id));
			} else {
				$this->Session->setFlash(__('The User could not be saved. Please, try again.', true));
			}
		} else {
			$this->data = $this->User->read(null, $id);
		}
	}

	/**
	 * Delete a user
	 *
	 * @param string $id The ID of the user
	 * @return void
	 */
	public function admin_delete($id = null)
	{
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for User', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->User->delete($id)) {
			$this->Session->setFlash(__('User deleted', true));
			$this->redirect(array('action'=>'index'));
		}
	}

}

?>
