<?php
/**
 * Full Metal Jewelry website
 * Copyright (C) 2010 Stichting Lone Wolves
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
		'Address' => array('dependent' => true)
	);

	/** @var array Many-to-many relationships */
	public $hasAndBelongsToMany = array(
		'Group' => array('unique' => true)
	);

	/** @var array Use Containable */
	public $actsAs = array('Containable');

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
