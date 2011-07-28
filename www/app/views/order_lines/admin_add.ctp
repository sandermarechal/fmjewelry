<div class="orderLines form">
<?php echo $form->create('OrderLine', array('url' => array('action' => 'add', $order_id)));?>
	<fieldset>
 		<legend>Add an order line</legend>
	<?php
		echo $form->input('name', array('label' => 'Name (e.g. "Customisation cost" or "Discount")'));
		echo $form->input('price', array('label' => 'Price (n&hellip;nn.nn format). For discounts use a negative value.', 'default' => '0.00'));
		echo $form->input('quantity', array('default' => 1));
	?>
	</fieldset>
<?php echo $form->end('Save');?>
</div>
