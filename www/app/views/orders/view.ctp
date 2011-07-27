<?php if ($this->action != 'admin_view'): ?>
    <?php if ($thanks): ?>
        <h2>Thank you</h2>
        <p>You order has been recieved and forwarded to our sales department.<br />You should receive your invoice within 48 hours.</p>
    <?php endif; ?>
<?php endif; ?>

<div id="orderView">
	<h2>Order nr. <?php printf('%06d', $order['Order']['id']); ?></h2>

	<dl>
		<dt>Submitted</dt>
		<dd>
			<?php echo $this->action == 'admin_view' ? $order['Order']['created'] : substr($order['Order']['created'], 0, 10); ?>
		</dd>

		<dt>Invoice sent</dt>
		<dd>
			<?php
				if ($this->action == 'admin_view') {
					if ($order['Order']['invoiced'] == '0000-00-00 00:00:00') {
						echo $this->Html->link('Invoice now', array('action' => 'invoice', $order['Order']['id']));
					} else {
						echo $order['Order']['invoiced'] == '0000-00-00 00:00:00' ? '-' : $order['Order']['invoiced'];
					}
				} else {
					echo $order['Order']['invoiced'] == '0000-00-00 00:00:00' ? '-' : substr($order['Order']['invoiced'], 0, 10);
				}
			?>
		</dd>

		<dt>Invoice paid</dt>
		<dd><?php echo $this->element('stamp', array('order' => $order, 'field' => 'paid')); ?></dd>

		<dt>Order shipped</dt>
		<dd><?php echo $this->element('stamp', array('order' => $order, 'field' => 'shipped')); ?></dd>
	</dl>

	<h3>Shipping information</h3>
	<?php echo $this->element('address', array('address' => $order['ShippingAddress'])); ?>

	<h3>Billing information</h3>
	<?php echo $this->element('address', array('address' => $order['BillingAddress'])); ?>

	<h3>Order details</h3>
    <table>
        <tr>
            <th>Item</th>
            <th>Quantity</th>
            <th>Price</th>
        </tr>
        <?php foreach ($order['OrderLine'] as $line): ?>
        <tr>
            <td class="item"><?php echo $line['name']; ?></td>
            <td class="qty"><?php echo $line['quantity']; ?></td>
            <td class="price">&euro; <?php echo $line['price_total']; ?></td>
        </tr>
        <?php endforeach; ?>
        <tr class="total">
            <td>Total</td>
            <td>&nbsp;</td>
            <td>&euro; <?php echo $order['Order']['price']; ?></td>
        </tr>
    </table>
	<?php
		$admin_edit = $this->action == 'admin_view' && $order['Order']['invoiced'] == '0000-00-00 00:00:00';
	?>
</div>

<div class="actions">
    <ul>
        <li>
            <?php
                if ($this->action == 'admin_view') {
                    echo $this->Html->link('Add a line', array('controller' => 'order_products', 'action' => 'add', $order['Order']['id']));
                } else {
                    echo $this->Html->link('Back', '/orders');
                }
            ?>
        </li>
    </ul>
</div>

