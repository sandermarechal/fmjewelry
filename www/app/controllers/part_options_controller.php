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
 * The PartOptions controller
 */
class PartOptionsController extends AppController
{
	/** @var array The view helpers */
	public $helpers = array('Html', 'Form');

	/**
	 * Add a part option to a part
	 * @param string $part_id The part ID
	 */
	public function admin_add($part_id = null)
	{
		if (empty($this->data)) {
			$this->redirect(array('controller' => 'parts', 'action' => 'view', $part_id));
		}

		$this->data['PartOption']['part_id'] = $part_id;
		$this->PartOption->create();
		if ($this->PartOption->save($this->data)) {
			$this->Session->setFlash(__('The PartOption has been saved', true));
		} else {
			$this->Session->setFlash(__('The PartOption could not be saved. Please, try again.', true));
		}

		$this->redirect(array('controller' => 'parts', 'action' => 'view', $part_id));
	}

	/**
	 * Delete a part option
	 */
	public function admin_delete($id = null)
	{
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for Part', true));
			$this->redirect(array('controller' => 'parts', 'action' => 'index'));
		}

		$option = $this->PartOption->read(null, $id);
		if ($this->PartOption->del($id)) {
			$this->Session->setFlash(__('Part deleted', true));
		}
		
		$this->redirect(array('controller' => 'parts', 'action' => 'view', $option['PartOption']['part_id']));
	}

	/**
	 * Set the option as the default part option
	 */
	public function admin_set_default($id = null)
	{
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for Part', true));
			$this->redirect(array('controller' => 'parts', 'action' => 'index'));
		}

		$option = $this->PartOption->read(null, $id);
		if (!$this->PartOption->setDefault($id)) {
			$this->Session->setFlash(__('Could not set part as default.', true));
		}
		
		$this->redirect(array('controller' => 'parts', 'action' => 'view', $option['PartOption']['part_id']));
	}
}

?>
