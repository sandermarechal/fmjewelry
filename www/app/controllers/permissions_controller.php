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
 * Controller for the permissions
 *
 * This controller only contains admin functions.
 */
class PermissionsController extends AppController
{
	/** @var array The helpers that will be available on the view */
	public $helpers = array('Html', 'Form','Button');

	/** @var array The components this controller uses */
	public $components = array('Auth');

	/**
	 * Add a new permission to the specified group
	 *
	 * @param string $group_id The Group to add the permission to
	 * @return void
	 */
	function admin_add($group_id = null)
	{
		if (!$group_id) {
			$this->Session->setFlash(__('Invalid group ID', true));
			$this->redirect(array('controller' => 'groups', 'action'=>'index'));
		}

		if (!empty($this->data)) {
			$this->Permission->create();
			$this->data['Permission']['group_id'] = $group_id;
			if ($this->Permission->save($this->data)) {
				$this->Session->setFlash(__('The Permission has been saved', true));
				$this->redirect(array('controller' => 'groups', 'action'=>'view', $group_id));
			} else {
				$this->Session->setFlash(__('The Permission could not be saved. Please, try again.', true));
			}
		}

		$this->set(compact('group_id'));
	}

	/**
	 * Delete a permission
	 *
	 * @param string $id The permission ID
	 * @return void
	 */
	function admin_delete($id = null)
	{
		if (!$id) {
			$this->Session->setFlash(__('Invalid permission ID', true));
			$this->redirect(array('controller' => 'groups', 'action'=>'index'));
		}

		$this->Permission->id = $id;
		$group_id = $this->Permission->field('group_id');

		if ($this->Permission->del($id)) {
			$this->Session->setFlash(__('Permission deleted', true));
			$this->redirect(array('controller' => 'groups', 'action'=>'view', $group_id));
		}
	}
}

?>
