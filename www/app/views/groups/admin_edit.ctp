<div class="groups form">
<?php echo $form->create('Group');?>
	<fieldset>
		<legend>
		<?php if ($this->action == 'admin_add') {
			__('Add Group');
		} else {
			__('Edit Group');
		}
		?>
		</legend>
	<?php
		echo $form->input('id');
		echo $form->input('name');
		echo $form->input('default', array('label' => 'New and existing users should be added to this group automatically'));
	?>
	</fieldset>
<?php echo $form->end('Save');?>
</div>
