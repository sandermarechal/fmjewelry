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
 * Controller for the OrderLines model
 */
class OrderLinesController extends AppController
{
    /** @var array The view helpers */
    public $helpers = array('Html', 'Form', 'Button');

    /** @var array The components for this controller */
    public $components = array('Auth');

    /**
     * Add a new OrderLine to the order
     * @param string $order_id The Order ID to add the product to
     */
    public function admin_add($order_id = null)
    {
        if (!empty($this->data)) {
            $this->OrderLine->create();
            $this->data['OrderLine']['order_id'] = $order_id;

            if ($this->OrderLine->save($this->data)) {
                $this->Session->setFlash(__('The OrderLine has been saved', true));
                $this->redirect(array('controller' => 'orders', 'action'=>'view', $order_id));
            } else {
                $this->Session->setFlash(__('The OrderLine could not be saved. Please, try again.', true));
            }
        }

        $this->set('order_id', $order_id);
    }

    /**
     * Edit an OrderLine
     * @param string $id The OrderLine ID
     */
    public function admin_edit($id = null)
    {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid OrderLine', true));
            $this->redirect(array('controller' => 'orders', 'action'=>'index'));
        }

        $this->OrderLine->id = $id;
        $order_id = $this->OrderLine->field('order_id');

        if (!empty($this->data)) {
            if ($this->OrderLine->save($this->data)) {
                $this->Session->setFlash(__('The OrderLine has been saved', true));
                $this->redirect(array('controller' => 'orders', 'action'=>'view', $order_id));
            } else {
                $this->Session->setFlash(__('The OrderLine could not be saved. Please, try again.', true));
            }
        } else {
            $this->data = $this->OrderLine->read(null, $id);
        }
    }

    /**
     * Delete an OrderLine
     * @param string $id The OrderLine ID
     */
    public function admin_delete($id = null)
    {
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for OrderLine', true));
            $this->redirect(array('controller' => 'orders', 'action'=>'index'));
        }

        $this->OrderLine->id = $id;
        $order_id = $this->OrderLine->field('order_id');

        if ($this->OrderLine->delete()) {
            $this->Session->setFlash(__('OrderLine deleted', true));
            $this->redirect(array('controller' => 'orders', 'action'=>'view', $order_id));
        }
    }
}

?>
