<div class="orders form">
<?php echo $form->create('Order');?>
	<fieldset>
 		<legend><?php __('Edit Order');?></legend>
	<?php
		echo $form->input('id');
		echo $form->input('price');
		echo $form->input('invoiced', array('dateFormat' => 'YMD', 'timeFormat' => '24', 'interval' => 15));
		echo $form->input('paid', array('dateFormat' => 'YMD', 'timeFormat' => '24', 'interval' => 15));
		echo $form->input('ordered', array('dateFormat' => 'YMD', 'timeFormat' => '24', 'interval' => 15));
		echo $form->input('shipped', array('dateFormat' => 'YMD', 'timeFormat' => '24', 'interval' => 15));
	?>
	</fieldset>
	<div class="submit">
		<?php echo $button->submit('Submit', array('div' => false));?>
		<?php echo $button->link(__('Delete', true), array('action'=>'delete', $form->value('Order.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $form->value('Order.id'))); ?>
	</div>
<?php echo $form->end();?>
</div>
