<h2>Your orders</h2>

<p>This is an overview of all your current and past orders. If you have questions about any of these orders then
<a href="/pages/contact">Please contact us</a> and quote the order number.</p>

<?php if (sizeof($orders)): ?>

	<table id="ordersIndex">
		<tr>
			<th>Order no.</th>
			<th>Amount</th>
			<th>Shipping date</th>
		</tr>
		<?php foreach ($orders as $order): ?>
			<tr>
				<td><a href="/orders/view/<?php echo $order['Order']['id'];?>"><?php printf('%06d', $order['Order']['id']); ?></a></td>
				<td class="price"><span class="currency">$</span> <?php echo number_format($order['Order']['price'], 2); ?></td>
				<td><?php echo $order['Order']['shipped'] == '0000-00-00 00:00:00' ? '-' : substr($order['Order']['shipped'], 0, 10); ?></td>
			</tr>
		<?php endforeach; ?>
	</table>

<?php else: ?>
	<p><em>You have not placed any orders yet.</em></p>
<?php endif; ?>

