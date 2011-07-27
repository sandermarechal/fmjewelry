<?php
/**
 * De Hospitaalridders website
 * Copyright (C) 2011 Stichting Lone Wolves
 * Written by Sander Marechal <s.marechal@jejik.com>
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 */

/**
 * Controller for the contact forms
 */
class ContactController extends AppController
{
	/** @var array The view helpers */
	public $helpers = array('Html', 'Form', 'Button');

	/** @var array The components this controller uses */
	public $components = array('Session', 'Auth', 'Email', 'RequestHandler');

	/** @var array Do not use any models */
	public $uses = array();

	/**
	 * Set the auth permissions for this controller
	 * @return void
	 */
	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->Auth->allow('index');
	}

	/**
	 * A generic contact function. Anything POST'ed to here will be put into an e-mail
	 * and sent to the administrator's address
	 */
	public function index()
	{
		if (empty($this->data)) {
			$this->render('error');
			return;
		}

		$this->set('data', $this->data);
		$this->set('ipAddress', $this->RequestHandler->getClientIP());

		$this->Email->from = Configure::read('Email.admin');
		$this->Email->to = Configure::read('Email.admin');
		$this->Email->reply_to = Configure::read('Email.admin');
		$this->Email->subject = '[Full Metal Jewelry] Message from the website';
		$this->Email->template = 'message';
		$this->Email->sendAs = 'text';

		if (!$this->Email->send()) {
			$this->Session->setFlash('The message could not be sent.');
			$this->redirect($this->referrer());
		}

		$this->render('thanks');
	}
}

?>
