<h2>Invoice order nr. <a href="/admin/orders/view/<?php echo $order['Order']['id'];?>"><?php printf('%06d', $order['Order']['id']); ?></a></h2>

<p>You can edit the entire invoice below. Note that if you change the invoice details or
monetary values that they will <em>not</em> be changed in the database but just on the
invoice. Please edit the products in the order themselves before sending the invoice.</p>

<form method="post" action="/admin/orders/invoice">
	<input type="hidden" name="data[Order][id]" value="<?php echo $order['Order']['id'];?>" />

	<div class="input">
		<label for="">Invoice text</label>
        <textarea name="data[Order][invoice_text]" cols="80" rows="30" class="monospace">
            <?php echo $this->element('invoice', array(
                'cart' => $order['OrderLine'],
                'price_total' => $order['Order']['price'],
                'admin' => $admin,
            )); ?>
        </textarea>
	</div>

<?php echo $this->Form->end('Send invoice');?>
