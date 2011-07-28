Hello <?php echo $order['User']['name']; ?>,

Thank you for placing an order with my Full Metal Jewelry. This is
your electronic invoice.

Shipping address
----------------

<?php echo str_replace('<br />', "\n", $this->element('address', array('address' => $order['ShippingAddress']))); ?>


Billing address
----------------

<?php echo str_replace('<br />', "\n", $this->element('address', array('address' => $order['BillingAddress']))); ?>


Order details
-------------

<?php
/**
 * This generates the order details table
 */
foreach ($cart as $index => $product) {
	// Convert data to the right format
	if (!isset($product['OrderLine'])) {
		$product = array('OrderLine' => $product);
	}

	echo "\n";
	printf('%4d x %-40s $ %10s',
		$product['OrderLine']['quantity'],
		$product['OrderLine']['name'],
		number_format($product['OrderLine']['price_total'], 2)
	);
	echo "\n";
}

echo "\n" . str_repeat('-', 60) . " +\n";
echo 'Total' . str_repeat(' ', 43) . sprintf('$ %10s', number_format($order['Order']['price'], 2)) . "\n";
echo str_repeat('=', 60) . "\n";

?>


Please send EUR <?php echo number_format($order['Order']['price'], 2); ?> via PayPal to the following address:

sales@fullmetaljewelry.com

If you are unable to pay via PayPal, please contact us
for other payment options.

Sincerely, 

-- 
<?php echo $admin; ?>

Full Metal Jewelry
