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
 * The Address Model
 */
class Address extends AppModel
{
	/** @var array Addresses belong to users */
	public $belongsTo = array('User');

	/** @var array Validation rules for the model */
	public $validate = array(
		'name' => array(
			'rule' => 'notEmpty',
			'message' => 'You must enter an addressee name',
		),
		'address_1' => array(
			'rule' => 'notEmpty',
			'message' => 'You must prodivide an address',
		),
		'city' => array(
			'rule' => 'notEmpty',
			'message' => 'You must prodivide a city',
		),
	);

	/**
	 * Set the address as the primary address
	 * 
	 * @param string $id The Address ID
	 * @return boolean Success
	 */
	public function setPrimary($id = null)
	{
		if ($id == null) {
			$id = $this->id;
		}

		$address = $this->find('first', array('conditions' => array(
			'Address.id' => $id
		)));

		if (!$address) {
			return false;
		}

		$this->updateAll(
			array('Address.primary' => 0),
			array('Address.user_id' => $address['Address']['user_id'])
		);

		$this->create(false);
		$this->id = $id;
		return $this->saveField('primary', 1);
	}

	/**
	 * If the address has been used for orders, use copy-on-write and mark the original as deleted
	 * @return boolean True to continue saving, false to abort
	 */
	public function beforeSave()
	{
		if ($this->id) {
			$Order =& ClassRegistry::init('Order');

			if ($Order->hasAddress($this->id)) {
				$fields_to_check = array('name', 'address_1', 'address_2', 'postal_code', 'city', 'state', 'country');
				foreach ($fields_to_check as $field) {
					if (isset($this->data['Address'][$field])) {
						// Changing an address already used by an Order. Use CoW.
						$this->saveField('deleted', true);

						$this->__exists = false;
						$this->id = false;
						$this->data['Address']['id'] = false;
						$this->data['Address']['created'] = date('Y-m-d H:i:s');
						$this->data['Address']['modified'] = date('Y-m-d H:i:s');

						return true;
					}
				}
			}
		}

		return true;
	}

	/**
	 * If the address has been used for orders, use only mark as deleted
	 * 
	 * @param boolean $cascade Cascade the delete down to child models
	 * @return boolean True to continue deleting, false to abort
	 */
	public function beforeDelete($cascade)
	{
		// TODO: Remove this when the order model has been copied
		return true;

		if ($this->id) {
			$Order =& ClassRegistry::init('Order');
			if ($Order->hasAddress($this->id)) {
				$this->saveField('deleted', true);
				return false;
			}
		}

		return true;
	}
}

?>
