<div class="parts form">
<?php echo $form->create('Part');?>
	<fieldset>
		<legend>
			<?php
			if ($this->action == 'admin_add') {
				__('Add part');
			} else {
				__('Edit part');
			}
			?>
		</legend>
		<?php
			if ($this->action == 'admin_edit') {
				echo $form->input('id');
			}
			echo $form->input('name', array('after' => '<p>A distinctive name. Only seen by admins. E.g. "Hard drives for series Foo netbooks".</p>'));
			echo $form->input('label', array('after' => '<p>This will be seen during product configuration. E.g. "Hard drive".</p>'));
			echo $form->input('Product', array('multiple' => 'checkbox', 'label' => __('Products', true)));
		?>
	</fieldset>
	<?php echo $button->submit('Submit');?>
<?php echo $form->end();?>
</div>
