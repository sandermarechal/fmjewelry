<h2>Contact us</h2>

<p>Please use the following form to get in touch with us.</p>

<?php echo $form->create(false, array('url' => '/contact'));?>
	<fieldset>
		<?php
			echo $form->input('name', array('label' => 'Name'));
			echo $form->input('e-mail_address', array('label' => 'E-mail address'));
			echo $form->input('subject', array('label' => 'Subject'));
			echo $form->input('message', array('type' => 'textarea', 'label' => 'Message'));
		?>
	</fieldset>
<?php echo $form->end('Send');?>
