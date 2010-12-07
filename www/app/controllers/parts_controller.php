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
 * The Parts controller
 */
class PartsController extends AppController
{
	/** @var array The view helpers */
	public $helpers = array('Html', 'Form', 'Button');

	public function admin_index()
	{
		$this->Part->recursive = 0;
		$this->set('parts', $this->paginate());
	}

	public function admin_view($id = null)
	{
		if (!$id) {
			$this->Session->setFlash(__('Invalid Part.', true));
			$this->redirect(array('action'=>'index'));
		}

		$part = $this->Part->find('first', array(
			'contain' => array(
				'PartOption' => array('order' => 'PartOption.price ASC'),
				'Product',
			),
			'conditions' => array(
				'Part.id' => $id,
			),
		));

		$this->set('part', $part);
	}

	public function admin_add()
	{
		if (!empty($this->data)) {
			$this->Part->create();
			if ($this->Part->save($this->data)) {
				$this->Session->setFlash(__('The Part has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The Part could not be saved. Please, try again.', true));
			}
		}
		$products = $this->Part->Product->find('list');
		$this->set(compact('products'));
		$this->render('admin_edit');
	}

	public function admin_edit($id = null)
	{
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Part', true));
			$this->redirect(array('action'=>'index'));
		}
		if (!empty($this->data)) {
			if ($this->Part->save($this->data)) {
				$this->Session->setFlash(__('The Part has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The Part could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Part->read(null, $id);
		}
		$products = $this->Part->Product->find('list');
		$this->set(compact('products'));
	}

	public function admin_delete($id = null)
	{
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for Part', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Part->del($id)) {
			$this->Session->setFlash(__('Part deleted', true));
			$this->redirect(array('action'=>'index'));
		}
	}

}
?>
