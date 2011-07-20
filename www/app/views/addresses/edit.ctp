<div class="addresses form">
<?php echo $form->create('Address');?>
	<fieldset>
		<legend>
		<?php if ($this->action == 'add' || $this->action == 'admin_add') {
			__('Add an address');
		} else {
			__('Edit address');
		}
		?>
		</legend>
		<?php
			echo $form->input('id');
			if ($this->action == 'admin_add' || $this->action == 'admin_edit') {
				echo $form->input('user_id');
			}
			echo $form->input('name');
			echo $form->input('address_1', array('label' => 'Address line 1'));
			echo $form->input('address_2', array('label' => 'Address line 2'));
			echo $form->input('postal_code');
			echo $form->input('city');
			echo $form->input('state');
			echo $form->input('country');
		?>
	</fieldset>
<?php echo $form->end('Save');?>
</div>
