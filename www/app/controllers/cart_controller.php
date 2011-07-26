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
 * Controller for the shopping cart
 */
class CartController extends AppController
{
	/** @var array The view helpers */
	var $helpers = array('Html', 'Form', 'Button');

	/** @var array The components this controller uses */
	public $components = array('Session', 'Auth');
	
	/** The models this controller uses */
	public $uses = array('Product', 'Address');

	/**
	 * Set the auth permissions for this controller
	 * @return void
	 */
	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->Auth->allow('index', 'add', 'delete', 'clear', 'checkout');

		if (!is_array($this->Session->read('Order'))) {
			$this->Session->write('Order', array('Order' => array()));
		}

		if (!is_array($this->Session->read('Cart'))) {
			$this->Session->write('Cart', array());
		}
	}

	/**
	 * Calculate the total price for a series of products
	 * @param array $products An array of items
	 * @return string The total price
	 */
	private function _getPriceTotal($items)
	{
		$price_total = '0.00';
		foreach ($items as $item) {
			$price_total = bcadd($price_total, $item['price_total'], 2);
		}

		return $price_total;
	}

	/**
	 * Set the cart contents and total price in the view
	 */
	private function _setCart()
	{
		$cart = $this->Session->read('Cart');
		$price_total = $this->_getPriceTotal($cart);

		$this->set(compact('cart', 'price_total'));
	}

	/**
	 * View the contents of the shopping cart
	 */
	public function index()
	{
		$this->_setCart();
	}

	/**
	 * Add a new product to the shopping cart.
	 */
	public function add()
	{
		$product = $this->Product->read(array('name', 'slug', 'price'), $this->data['Product']['id']);
		$cart = $this->Session->read('Cart');

        if (isset($cart[$product['Product']['slug']])) {
            $item =& $cart[$product['Product']['slug']];
            $item['quantity']++;
            $item['price_total'] = bcmul($item['quantity'], $item['price'], 2);
        } else {
            $product['Product']['quantity'] = 1;
            $product['Product']['price_total'] = $product['Product']['price'];
            $cart[$product['Product']['slug']] = $product['Product'];
        }

		$this->Session->write('Cart', $cart);
        $this->Session->setFlash(sprintf('One "%s" has been added to your cart.', $product['Product']['name']));
        $this->redirect('/cart');
	}

	/**
	 * Delete an item from the cart
	 * @param $index The array index to remove
	 */
	public function delete($index)
	{
		$cart = $this->Session->read('Cart');
		unset($cart[$index]);
		$this->Session->write('Cart', $cart);

		$this->redirect(array('action' => 'index'));
	}

	/**
	 * Empty the shopping cart
	 */
	public function clear()
	{
		$this->Session->write('Cart', array());
		$this->redirect(array('action' => 'index'));
	}

	/**
	 * Checkout redirects to the next (or previous) step of the checkout process.
	 * @param string $previous When set, redirect to the previous step instead of to the next step.
	 */
	public function checkout($previous = false)
	{
		// Make sure the user has an account and is authenticated
		if (!$this->Auth->user()) {
			$this->Session->write('Auth.redirect', '/cart/checkout');
			$this->redirect(array('controller' => 'users', 'action' => 'login'));
		}

		// Step back
		if ($previous) {
			if ($this->Session->check('Order.Order.billing_address_id') && $this->Session->read('Order.Order.billing_address_id')) {
				$this->Session->delete('Order.Order.billing_address_id');
				$this->redirect(array('controller' => 'addresses', 'action' => 'select', 'billing'));
			}

			if ($this->Session->check('Order.Order.shipping_address_id') && $this->Session->read('Order.Order.shipping_address_id')) {
				$this->Session->delete('Order.Order.shipping_address_id');
				$this->redirect(array('controller' => 'addresses', 'action' => 'select', 'shipping'));
			}

			$this->redirect(array('action' => 'index'));
		}

		// Step forward
		if (!$this->Session->check('Cart') || !$this->Session->read('Cart')) {
			$this->Session->setFlash('Your shopping cart is empty. You have nothing to check out. Start <a href="/">shopping</a>.');
			$this->redirect(array('action' => 'index'));
		}

		if (!$this->Session->check('Order.Order.shipping_address_id') || !$this->Session->read('Order.Order.shipping_address_id')) {
			$this->redirect(array('controller' => 'addresses', 'action' => 'select', 'shipping'));
		}

		if (!$this->Session->check('Order.Order.billing_address_id') || !$this->Session->read('Order.Order.billing_address_id')) {
			$this->redirect(array('controller' => 'addresses', 'action' => 'select', 'billing'));
		}

		$this->redirect(array('action' => 'confirm'));
	}

	/**
	 * Review and confirm the order. At this stage it woll be stored in the database and an actual
	 * Order object will be created.
	 */
	public function confirm()
	{
		if (!empty($this->data)) {
			if ($this->data['confirm']) {
				$order = $this->Session->read('Order');
				$products = $this->Session->read('Cart');
				$price_total = $this->_getPriceTotal($products);

				$order['Order']['user_id'] = $this->Auth->user('id');
				$order['Order']['price'] = $price_total;

				// Create the order
				$this->Order->create();
				if (!$this->Order->save($order)) {
					$this->Session->setFlash('The order could not be created');
					$this->redirect();
				}

				// Add the cart contents to the order
				foreach ($products as $product) {
					$product['OrderProduct']['order_id'] = $this->Order->id;

					$this->OrderProduct->create();
					if (!$this->OrderProduct->saveAll($product)) {
						$this->Session->setFlash('The ' . $product['OrderProduct']['name'] . ' could not be added to the order. The order has been cancelled.');
						$this->Order->delete();
						$this->redirect();
					}
				}

				// If we made it so far then the order has been completed. Clear the cart and show a receipt.
				$this->Session->delete('Cart');
				$this->Session->delete('Order');
				$this->redirect(array('controller' => 'orders', 'action' => 'view', $this->Order->id, 'thanks'));
			}
		}

		$this->_setCart();

		$this->Address->recursive = -1;
		$shippingAddress = $this->Address->read(null, $this->Session->read('Order.Order.shipping_address_id'));
		$billingAddress  = $this->Address->read(null, $this->Session->read('Order.Order.billing_address_id'));
		$emailAddress    = $this->Auth->user('email_address');

		$this->set(compact('shippingAddress', 'billingAddress', 'emailAddress'));
	}
}

?>
