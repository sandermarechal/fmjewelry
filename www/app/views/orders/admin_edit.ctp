<div class="orders form">
<?php echo $form->create('Order', array('id' => 'editOrder'));?>
	<fieldset>
 		<legend>Edit order <?php printf('%06d', $this->data['Order']['id']);?></legend>
	<?php
		echo $form->input('id');
		echo $form->input('price');
		echo $form->input('invoiced', array('dateFormat' => 'YMD', 'timeFormat' => '24', 'interval' => 15));
		echo $form->input('paid', array('dateFormat' => 'YMD', 'timeFormat' => '24', 'interval' => 15));
		echo $form->input('shipped', array('dateFormat' => 'YMD', 'timeFormat' => '24', 'interval' => 15));
	?>
	</fieldset>
	<div class="actions submit">
        <ul>
            <li><?php echo $this->Html->link('Submit', '#', array('id' => 'submit')); ?></li>
            <li><?php echo $this->Html->link(__('Delete', true), array('action'=>'delete', $form->value('Order.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $form->value('Order.id'))); ?></li>
        </ul>
	</div>
<?php echo $form->end();?>
</div>

<script type="text/javascript">
	$(function() {
        $('#submit').click(function() {
            $('#editOrder').submit();
            return false;
        })
	});
</script>
