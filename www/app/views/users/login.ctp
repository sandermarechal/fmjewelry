<?php
	if ($session->check('Message.flash')) {
		echo $session->flash();
	}
	if ($session->check('Message.auth')) {
		echo $session->flash('auth');
	}
?>

<p><?php echo $letter->img('E');?>nter your account details to log in. If you cannot remember your password,
you can use our <a href="/users/recover">password recovery</a> form to reset your password.</p>

<?php echo $form->create('User', array('action' => 'login')); ?>
<fieldset>
	<?php
		echo $form->input('email_address', array('between' => '<br>', 'class' => 'text'));
		echo $form->input('password', array('between' => '<br>', 'class' => 'text'));
	?>
</fieldset>
<?php echo $button->submit('Log in');?>
<?php echo $form->end(); ?>

