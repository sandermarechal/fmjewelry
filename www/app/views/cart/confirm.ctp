<h2>Confirm your order</h2>

<form id="cartConfirm" method="post" action="/cart/confirm">

<fieldset>
	<legend>Invoice</legend>
	<p>The invoice will be sent to: <strong><?php echo $emailAddress; ?></strong>.</p>
	<p>The invoice will be addressed to:</p>
	<?php echo $this->element('address', array('address' => $billingAddress)); ?>
</fieldset>

<fieldset>
	<legend>Shipping</legend>
	<p>The order will be shipped to:</p>
	<?php echo $this->element('address', array('address' => $shippingAddress)); ?>
</fieldset>

<fieldset>
	<legend>Order</legend>
    <table>
        <tr>
            <th>Item</th>
            <th>Quantity</th>
            <th>Price</th>
        </tr>
        <?php foreach ($cart as $index => $item): ?>
        <tr>
            <td class="item"><?php echo $this->Html->link($item['name'], array('controller' => 'products', 'action' => 'view', $item['slug'])); ?></td>
            <td class="qty"><?php echo $item['quantity']; ?></td>
            <td class="price">&euro; <?php echo $item['price_total']; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</fieldset>

<div class="actions submit">
    <ul>
	    <li><?php echo $this->Html->link('Back', '/cart/checkout/back'); ?></li>
	    <li><?php echo $this->Html->link('Continue', '#', array('id' => 'submit')); ?></li>
    </ul>
</div>

</form>

<script type="text/javascript">
	$(function() {
        $('#submit').click(function() {
            $('#AddressSelectForm').submit();
            return false;
        })
	});
</script>
