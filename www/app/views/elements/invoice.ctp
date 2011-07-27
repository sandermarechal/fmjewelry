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

Please wire the $ <?php echo number_format($order['Order']['price'], 2); ?> to my secret account in the Bahama's
under account number 000-l33t-a5-h311. If you do not then I will
send my goon squad around, m'kay?

Sincerely, 

-- 
<?php echo $admin; ?>

Full Metal Jewelry
