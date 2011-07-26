<div id="cart">

<?php foreach ($cart as $index => $product): ?>
	<?php
		if (!isset($remove)) $remove = false;
		if (!isset($admin_edit)) $admin_edit = false;

		// Convert data to the right format
		if (!isset($product['OrderProduct'])) {
			$product = array('OrderProduct' => $product, 'OrderPart' => $product['OrderPart']);
			unset($product['Order']['OrderPart']);
		}
	?>
	<table class="cart">
		<tr>
			<td>
				<strong><?php echo $product['OrderProduct']['quantity'] . ' x ' . $product['OrderProduct']['name']; ?></strong>
				<?php if ($remove): ?>
					(<a href="/cart/delete/<?php echo $index;?>">remove</a>)
				<?php endif; ?>
				<?php if ($admin_edit): ?>
					(<a href="/admin/order_products/delete/<?php echo $product['OrderProduct']['id'];?>">remove</a>)
				<?php endif; ?>
				<?php if ($admin_edit): ?>
					(<a href="/admin/order_products/edit/<?php echo $product['OrderProduct']['id'];?>">edit</a>)
				<?php endif; ?>
			</td>
			<td class="price"><span class="currency">$</span> <?php echo number_format($product['OrderProduct']['price'], 2); ?></td>
		</tr>
		<?php foreach ($product['OrderPart'] as $part): ?>
			<tr>
				<td class="part"><?php echo $part['name']; ?></td>
				<td class="price"><span class="currency">$</span> <?php echo number_format($part['price'], 2); ?></td>
			</tr>
		<?php endforeach; ?>
		<?php if (sizeof($product['OrderPart'])): ?>
			<tr class="subtotal">
				<td class="part">Subtotal</td>
				<td class="price"><span class="currency">$</span> <?php echo number_format($product['OrderProduct']['price_total'], 2); ?></td>
			</tr>
		<?php endif; ?>
	</table>
<?php endforeach; ?>

<table class="cart total">
	<tr>
		<td>Total</td>
		<td class="price"><span class="currency">&euro;</span> <?php echo number_format($price_total, 2); ?></td>
	</tr>
</table>

</div>
