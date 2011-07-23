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
 * The Mailers Controller
 */
class MailersController extends AppController
{
	/** @var array The components this controller uses */
	public $components = array('Auth');
	
	/** @var array The helpers for the controller */
	public $helpers = array('Html', 'Form');

    /** @var array The models to use */
    public $uses = array('Category', 'User');

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
        $this->redirect('/');
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

		$this->User->contain('Group');
		$mailer =  $this->User->findBySlug($slug);
		if (!$mailer) {
			$this->Session->setFlash(__('Invalid mailer.', true));
			$this->redirect('/');
		}

        if (!in_array('Mailers', Set::extract('/Group/name', $mailer))) {
			$this->Session->setFlash(__('Invalid mailer.', true));
			$this->redirect('/');
        }

		$subcategories = $this->Category->getSubcategories();
		$products = $this->User->getProducts($mailer['User']['id']);
        $mailers = $this->User->getMailers();

		$this->set(compact('mailer', 'subcategories', 'products', 'mailers'));
	}
}
