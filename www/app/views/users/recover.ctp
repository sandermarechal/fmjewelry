<div class="users form">
<p><?php echo $letter->img('E');?>nter your e-mail address. We will send you an e-mail that contains instructions to reset
your account password.</p>

<?php echo $form->create('User', array('action' => 'recover'));?>
	<fieldset>
		<?php
			echo $form->input('email_address', array('value' => ''));
		?>
	</fieldset>
	<?php echo $button->submit('Recover');?>
<?php echo $form->end();?>
</div>
