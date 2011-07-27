<?php
/**
 * Full Metal Jewelry website
 * Copyright (C) 2011 Stichting Lone Wolves
 * Written by Sander Marechal <s.marechal@jejik.com>
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 */

App::import('Core', 'Security');

/**
 * The basic User model
 */
class User extends AppModel
{
	/** @var string The primary field */
	public $displayField = 'email_address';

	/** @var order by e-mail address */
	public $order = array('User.email_address' => 'asc');

	/** @var array The validation model */
	public $validate = array(
		'email_address' => array('email'),
		'password' => array('alphaNumeric'),
	);

	/** @var array One-to-many relationships */
	public $hasMany = array(
                'Address' => array('dependent' => true),
                'Image',
                'Product',
	);

	/** @var array Many-to-many relationships */
	public $hasAndBelongsToMany = array(
		'Group' => array('unique' => true)
	);

	/** @var array Use Containable */
    public $actsAs = array(
        'Containable',
		'Sluggable' => array('label' => 'name', 'overwrite' => true),
    );

	/**
	 * Get the sha1 hash of a certain field
	 *
	 * @return string A hash
	 */
	private function getHash($field)
	{
		if (!$this->id) {
			return false;
		}

		return Security::hash($this->field($field), 'sha1', true);
	}

	/**
	 * Get the user activation hash
	 *
	 * @return string The hash
	 */
	public function getActivationHash()
	{
		return $this->getHash('created');
	}

	/**
	 * Get the user password recovery hash
	 *
	 * @return string The hash
	 */
	public function getRecoveryHash()
	{
		return $this->getHash('password');
	}

	/**
	 * Attempt to activate the user based on the supplied has value
	 *
	 * @param string $hash the hash to verify against
	 * @return boolean
	 */
	public function activate($hash)
	{
		if (!$this->id || !$hash) {
			return false;
		}

		if ($hash === $this->getActivationHash()) {
			$this->saveField('active', true);
			return true;
		}

		return false;
	}

    /**
     * Find all users that are member of the Mailers group
     * 
     * @return void
     */
    public function getMailers()
    {
        $group = $this->Group->find('first', array(
            'contain' => array('User'),
            'conditions' => array('Group.name' => 'Mailers'),
        ));

        $users = array();
        foreach ($group['User'] as $user) {
            $users[] = array('User' => $user);
        }

        return $users;
    }

	/**
	 * Get all the products for a user
	 * @param string $id The user ID
	 */
	public function getProducts($id = null)
	{
		if (!$id) {
			$id = $this->id;
		}

		$user = $this->find('first', array(
			'contain' => array(
				'Product',
                'Product.Image',
			),
			'conditions' => array('User.id' => $id),
		));

		$products = array();
		foreach ($user['Product'] as $product) {
            if (count($product['Image'])) {
                $this->Product->Image->id = $product['Image'][0]['id'];
                $product['image'] = $this->Product->Image->getPath(170);
                unset($product['Image']);
            }
			$products[] = array('Product' => $product);
		}

		return $products;
    }

	/**
	 * Convert the Markdown description to HTML before saving
	 * @return boolean True to continue saving
	 */
	public function beforeSave()
	{
		if (isset($this->data['User']['description'])) {
			App::import('Vendor', 'markdown');
			$this->data['User']['description_html'] = Markdown($this->data['User']['description']);
		}

		return true;
	}

	/**
	 * After creating a new user, add them to all the default groups
	 * @param boolean $created Whether the saved user was newly created or not
	 */
	public function afterSave($created)
	{
		if (!$created) {
			return;
		}

		$groups = $this->Group->find('all', array(
			'conditions' => array('Group.default' => 1),
		));

		foreach ($groups as $group) {
			$this->Group->addMember($this->id, $group['Group']['id']);
		}
	}
}

?>
