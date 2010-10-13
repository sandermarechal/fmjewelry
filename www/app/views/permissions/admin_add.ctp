<div class="permissions form">
<?php echo $form->create('Permission', array('url' => array('action' => 'add', $groupId)));?>
	<fieldset>
 		<legend><?php __('Add Permission');?></legend>
	<?php
		echo $form->input('name', array(
			'label' => false,
			'between' => __('Permissions are in the form of &lt;controller&gt;:&lt;action&gt;. You can use * as a wildcard.', true),
		));
	?>
	</fieldset>
	<?php echo $button->submit('Submit');?>
<?php echo $form->end();?>
</div>
