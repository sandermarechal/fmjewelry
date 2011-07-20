<div class="users form">
<p><?php echo $letter->img('E');?>nter your e-mail address. We will send you an e-mail that contains instructions to reset
your account password.</p>

<?php echo $form->create('User', array('action' => 'recover'));?>
	<fieldset>
                <legend>Recover your password</legend>
                <?php echo $form->input('email_address', array('value' => '')); ?>
	</fieldset>
<?php echo $form->end('Recover');?>
</div>
