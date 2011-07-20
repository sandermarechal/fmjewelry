<div class="users form">
<?php echo $form->create('User');?>
	<fieldset>
 		<legend><?php __('Edit your account information');?></legend>
		<?php
			echo $form->input('id');
			echo $form->input('name');
			echo $form->input('email_address');
		?>
	</fieldset>
	<fieldset>
		<legend><?php __('Change your password'); ?></legend>
		<?php
			echo $form->input('new_password', array('type' => 'password', 'value' => '', 'label' => __('Password', true)));
			echo $form->input('new_password_confirm', array('type' => 'password', 'value' => '', 'label' => __('Confirm password', true)));
		?>
	</fieldset>
<?php echo $form->end('Save');?>
</div>
