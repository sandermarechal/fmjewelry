<div class="orderLines form">
<?php echo $form->create('OrderLine');?>
	<fieldset>
 		<legend>Edit order line</legend>
		<?php
			echo $form->input('id');
			echo $form->input('name');
			echo $form->input('price');
			echo $form->input('quantity');
		?>
	</fieldset>
<?php echo $form->end('Save');?>
</div>
