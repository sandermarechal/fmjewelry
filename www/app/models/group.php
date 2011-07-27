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
 * The Group Model
 */
class Group extends AppModel
{
	public $actsAs = array('Containable');

	/** @var array Groups have multiple permissions */
	public $hasMany = array(
		'Permission' => array('dependent' => true, 'order' => 'Permission.name ASC'),
	);

	/** @var array The many-to-many relationships */
	public $hasAndBelongsToMany = array(
		'User' => array('unique' => true)
	);

	/**
	 * Add a member to a group
	 * @param string $user_id The ID of the user
	 * @param string $id The ID of the group
	 * @return boolean Success
	 */
	public function addMember($user_id, $id = null)
	{
		if ($id == null) {
			$id = $this->id;
		}

		$relation = $this->GroupsUser->find('first', array('conditions' => array(
			'group_id' => $id,
			'user_id' => $user_id,
		)));

		if (!empty($relation)) {
			return true;
		}

		$this->GroupsUser->create();
		return $this->GroupsUser->save(array('GroupsUser' => array(
			'group_id' => $id,
			'user_id' => $user_id,
		)));
	}

	/**
	 * Remove a member from the group
	 * @param string $user_id The ID of the user
	 * @param string $id The ID of the group
	 * @return boolean Success
	 */
	public function removeMember($user_id, $id = null)
	{
		if ($id == null) {
			$id = $this->id;
		}

		$relation = $this->GroupsUser->find('first', array('conditions' => array(
			'group_id' => $id,
			'user_id' => $user_id,
		)));

		if (!empty($relation)) {
			if (!$this->GroupsUser->del($relation['GroupsUser']['id'])) {
				return false;
			}
		}

		return true;
	}

	/**
	 * After creating a new 'default' group, add all existing users to it.
	 * @param boolean $created Whether the saved user was newly created or not
	 */
	public function afterSave($created)
	{
		if (!$created || !$this->data['Group']['default']) {
			return;
		}

		$users = $this->User->find('all', array(
			'recursive' => -1,
		));

		foreach ($users as $user) {
			$this->addMember($user['User']['id'], $this->id);
		}
	}
}

?>
