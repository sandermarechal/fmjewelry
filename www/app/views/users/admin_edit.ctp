<div class="users form">
<?php echo $form->create('User');?>
	<fieldset>
 		<legend><?php __('Edit User');?></legend>
		<?php
			echo $form->input('id');
			echo $form->input('name');
			echo $form->input('email_address');
			echo $form->input('new_password', array('type' => 'password'));
			echo $form->input('new_password_confirm', array('type' => 'password'));
			echo $form->input('active');
			echo $form->input('Group', array('multiple' => 'checkbox', 'label' => __('Group membership', true)));
		?>
	</fieldset>
	<?php echo $button->submit('Submit');?>
<?php echo $form->end();?>
</div>
