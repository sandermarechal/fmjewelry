<div class="users form">
<?php echo $form->create('User', array('action' => 'register'));?>
	<fieldset>
 		<legend><?php __('Register a new account');?></legend>
		<?php
			echo $form->input('name');
			echo $form->input('email_address');
			echo $form->input('password', array('value' => ''));
		?>
	</fieldset>
	<?php echo $button->submit('Register');?>
<?php echo $form->end();?>
</div>
