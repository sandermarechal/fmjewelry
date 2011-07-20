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
        <legend>Login</legend>
	<?php
		echo $form->input('email_address', array('class' => 'text'));
		echo $form->input('password', array('class' => 'text'));
	?>
</fieldset>
<?php echo $form->end('Log in'); ?>

