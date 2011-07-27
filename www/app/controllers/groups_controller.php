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
 * Controller for the Groups model
 *
 * This controller only contains admin functions.
 */
class GroupsController extends AppController
{
	/** @var array The helpers that will be available on the view */
	public $helpers = array('Html', 'Form', 'Button');

	/** @var array The components this controller uses */
	public $components = array('Auth');

	/** @var array The models that this controller uses */
	public $uses = array('Group', 'GroupsUser');

	/**
	 * List all the groups
	 *
	 * @return void
	 */
	public function admin_index()
	{
		$this->paginate = array(
			'contain' => array('User'),
		);
		$this->set('groups', $this->paginate());
	}

	/**
	 * View a single group and edit it's members and permissions
	 *
	 * @return void
	 */
	public function admin_view($id = null)
	{
		if (!$id) {
			$this->Session->setFlash(__('Invalid Group.', true));
			$this->redirect(array('action'=>'index'));
		}

		$this->set(array(
			'group' => $this->Group->read(null, $id),
		));
	}

	/**
	 * Create a new group
	 *
	 * @return void
	 */
	public function admin_add()
	{
		if (!empty($this->data)) {
			$this->Group->create();
			if ($this->Group->save($this->data)) {
				$this->Session->setFlash(__('The Group has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The Group could not be saved. Please, try again.', true));
			}
		}
		$permissions = $this->Group->Permission->find('list');
		$users = $this->Group->User->find('list');
		$this->set(compact('permissions', 'users'));

		$this->render('admin_edit');
	}

	/**
	 * Edit a group
	 *
	 * #param string $id The group ID
	 * @return void
	 */
	public function admin_edit($id = null)
	{
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Group', true));
			$this->redirect(array('action'=>'index'));
		}
		if (!empty($this->data)) {
			if ($this->Group->save($this->data)) {
				$this->Session->setFlash(__('The Group has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The Group could not be saved. Please, try again.', true));
			}
		} else {
			$this->data = $this->Group->read(null, $id);
		}
		$permissions = $this->Group->Permission->find('list');
		$users = $this->Group->User->find('list');
		$this->set(compact('permissions','users'));
	}

	/**
	 * Delete a group
	 *
	 * @return void
	 */
	public function admin_delete($id = null)
	{
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for Group', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Group->delete($id, true)) {
			$this->Session->setFlash(__('Group deleted', true));
			$this->redirect(array('action'=>'index'));
		}
	}

	/**
	 * Remove a member from the group
	 *
	 * @param string $id The ID of the group
	 * @param string $user_id The ID of the user
	 * @return void
	 */
	public function admin_removeMember($id = null, $user_id = null)
	{
		if (!$id || !$user_id) {
			$this->Session->setFlash(__('Invalid group membership', true));
			$this->redirect(array('action'=>'index'));
		}

		if ($this->Group->removeMember($user_id, $id)) {
			$this->Session->setFlash(__('The member has been removed', true));
			$this->redirect(array('action'=>'view', $id));
		}
		
		$this->Session->setFlash(__('Invalid group membership', true));
		$this->redirect(array('action'=>'view', $id));
	}
}
?>
