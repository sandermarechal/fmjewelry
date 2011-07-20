<div class="users form">
<?php echo $form->create('User', array('action' => 'reset'));?>
	<fieldset>
 		<legend><?php __('Reset your account password');?></legend>
		<?php
			echo $form->input('id');
			echo $form->hidden('hash', array('value' => $hash));
			echo $form->input('new_password', array('type' => 'password', 'value' => '', 'label' => __('Password', true)));
			echo $form->input('new_password_confirm', array('type' => 'password', 'value' => '', 'label' => __('Confirm password', true)));
		?>
	</fieldset>
<?php echo $form->end('Reset');?>
</div>
