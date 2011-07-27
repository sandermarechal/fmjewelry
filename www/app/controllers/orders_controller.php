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
 * Controller for the Order model
 */
class OrdersController extends AppController
{
    /** @var array The view helpers */
    public $helpers = array('Html', 'Form', 'Button');

    /** @var array The components for this controller */
    public $components = array('Auth', 'Email');

    /** @var array Set up pagination */
    public $paginate = array(
        'contain' => array('User'),
        'order' => 'Order.id DESC',
    );

    /**
     * List all orders
     */
    public function index()
    {
        $this->Order->recursive = -1;
        $orders = $this->Order->find('all', array(
            'conditions' => array('Order.user_id' => $this->Auth->user('id')),
            'order' => 'Order.id DESC',
        ));

        $this->set('orders', $orders);
    }

    /**
     * Show an order. If $thanks has been set then a 'Thank you for your order' message will be added.
     * @param int $id The order ID
     * @param string $thanks
     */
    public function view($id = null, $thanks = null)
    {
        if (!$id) {
            $this->redirect(array('action' => 'index'));
        }

        $order = $this->Order->find('first', array(
            'contain' => array(
                'OrderLine',
                'ShippingAddress',
                'BillingAddress',
                'User.email_address',
            ),
            'conditions' => array(
                'Order.id' => $id,
            ),
        ));

        $this->set(compact('order', 'thanks'));
    }

    /**
     * An overview of all orders
     * @param string $filter a user ID or 'all' or 'pending'
     */
    public function admin_index($filter = null)
    {
        if (!$filter && $this->Session->check('OrderFilter')) {
            $filter = $this->Session->read('OrderFilter');
        }

        if (!$filter) {
            $filter = 'pending';
        }

        $this->Session->write('OrderFilter', $filter);

        switch ($filter) {
            case 'all':
                $orders = $this->paginate();
                break;
            case 'pending':
                $orders = $this->paginate(array('Order.shipped' => '0000-00-00 00:00:00'));
                break;
            default:
                $orders = $this->paginate(array('Order.user_id' => $filter));
                $this->Order->User->recursive = -1;
                $this->set('user', $this->Order->User->read(null, $filter));
                break;
        }

        $this->set(compact('orders', 'filter'));
    }

    /**
     * View an order
     */
    public function admin_view($id = null)
    {
        if (!$id) {
            $this->Session->setFlash(__('Invalid Order.', true));
            $this->redirect(array('action'=>'index'));
        }

        $order = $this->Order->find('first', array(
            'contain' => array(
                'OrderLine',
                'ShippingAddress',
                'BillingAddress',
                'User',
            ),
            'conditions' => array(
                'Order.id' => $id,
            ),
        ));

        $this->set('order', $order);
        $this->render('view');
    }

    /**
     * Edit an order
     */
    public function admin_edit($id = null)
    {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid Order', true));
            $this->redirect(array('action'=>'index'));
        }
        if (!empty($this->data)) {
            if ($this->Order->save($this->data)) {
                $this->Session->setFlash(__('The Order has been saved', true));
                $this->redirect(array('action'=>'index'));
            } else {
                $this->Session->setFlash(__('The Order could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->Order->read(null, $id);
        }
        $shippingAddresses = $this->Order->ShippingAddress->find('list');
        $billingAddresses = $this->Order->BillingAddress->find('list');
        $users = $this->Order->User->find('list');
        $this->set(compact('shippingAddresses','billingAddresses','users'));
    }

    /**
     * Delete an order
     */
    public function admin_delete($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for Order', true));
            $this->redirect(array('action'=>'index'));
        }
        if ($this->Order->delete($id)) {
            $this->Session->setFlash(__('Order deleted', true));
            $this->redirect(array('action'=>'index'));
        }
    }

    /**
     * Send an invoice for the order
     * @param integer $id The Order number
     */
    public function admin_invoice($id = null)
    {
        if (isset($this->data)) {
            // Send the invoice via e-mail
            $this->Order->id = $this->data['Order']['id'];
            $this->Order->User->id = $this->Order->field('user_id');

            $this->set('invoice_text', $this->data['Order']['invoice_text']);

            $this->Email->to = $this->Order->User->field('email_address');
            $this->Email->cc = array(Configure::read('Email.admin'));
            $this->Email->subject = __('[Full Metal Jewelry] Invoice for order ' . sprintf('%06d', $this->data['Order']['id']), true);
            $this->Email->from = Configure::read('Email.admin');
            $this->Email->template = 'invoice';
            $this->Email->sendAs = 'text';
            $this->Email->delivery = 'mail'; // debug or mail
            
            if ($this->Email->send()) {
                App::import('Core', 'File');

                // Save the invoice to a txt file
                $file = new File(APP . 'files' . DS . 'invoices' . DS . 'invoice_' . $this->data['Order']['id'] . '.txt', true, 0775);
                $file->write($this->data['Order']['invoice_text']);
                $file->close();

                $this->Order->saveField('invoiced', date('Y-m-d H:i:s'));
                $this->redirect(array('action' => 'view', $this->data['Order']['id']));
            }

            $this->setFlash('The order could not be sent through e-mail');
            $id = $this->data['Order']['id'];
        }

        if (!$id) {
            $this->Session->setFlash(__('Invalid Order.', true));
            $this->redirect(array('action'=>'index'));
        }

        $order = $this->Order->find('first', array(
            'contain' => array(
                'OrderLine',
                'ShippingAddress',
                'BillingAddress',
                'User',
            ),
            'conditions' => array(
                'Order.id' => $id,
            ),
        ));

        $this->set(array(
            'order' => $order,
            'admin' => $this->Auth->user('name'),
        ));
    }

    /**
     * Set a stamp
     */
    public function admin_stamp()
    {
        if (isset($this->data)) {
            $this->Order->save($this->data);
            $this->redirect(array('action' => 'view', $this->Order->id));
        }
    }
}

?>
