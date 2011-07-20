<div class="categories form">
<?php echo $form->create('Category');?>
	<fieldset>
		<legend>
		<?php if ($this->action == 'admin_add') {
			__('Add Category');
		} else {
			__('Edit Category');
		}
		?>
		</legend>
		<?php
			if ($this->action == 'admin_edit') {
				echo $form->input('id');
			}
			echo $form->input('name');
			echo $form->input('image', array(
				'type' => 'select',
				'options' => $images,
				'empty' => '(none)',
				'after' => '<p>Upload images to app/webroot/img/categories. Roughly 75x75px with a <em>transparent</em> background.</p>'
			));
			echo $form->input('description', array('label' => 'Description (HTML)', 'default' => '<p>Enter a description in HTML</p>'));
		?>
	</fieldset>
<?php echo $form->end('Save');?>
</div>
