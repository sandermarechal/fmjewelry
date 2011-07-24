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
 * The Images controller
 */
class ImagesController extends AppController {

	/** @var array The view helpers */
	public $helpers = array('Button');

	/** @var array The components for this controller */
	public $components = array('Auth');

        /**
         * Nothing to see here, move along...
         * 
         * @return void
         */
        public function index()
        {
                $this->redirect('/');
	}

        public function admin_index()
        {
                $this->pagination = array(
                        'conditions' => array('Image.user_id' => $this->Auth->user('id')),
                        'order' => array('Image.filename' => 'ASC'),
                );

		$this->Image->recursive = 0;
                $images = $this->paginate();
                foreach ($images as &$image) {
                        $this->Image->id = $image['Image']['id'];
                        $this->Image->thumb(150);
                        $image['Image']['thumb'] = '/img/products/' . $this->Image->getPath(150);
                }

		$this->set(compact('images'));
	}

        public function admin_delete($id = null)
        {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for image', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Image->delete($id)) {
			$this->Session->setFlash(__('Image deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Image was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}

	/**
	 * Upload an image
	 */
	function admin_upload()
	{
		if (empty($this->data)) {
			$this->redirect(array('action' => 'admin_index'));
		}

		$this->Image->create();
		$this->Image->save(array('user_id' => $this->Auth->user('id')));

		$errors = array();
		if (!$this->Image->upload($this->data['image'], $errors)) {
			$this->Session->setFlash(implode('<br />', $errors));
			$this->Image->delete();
		}
		
                $this->Session->setFlash('Image uploaded succesfully');
		$this->redirect(array('action' => 'admin_index'));
	}
}

?>
