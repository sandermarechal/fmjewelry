<div class="products form">
<?php echo $form->create('Product');?>
	<fieldset>
		<legend>
			<?php
			if ($this->action == 'admin_add') {
				__('Add product');
			} else {
				__('Edit product');
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
				'after' => '<p>Upload images to app/webroot/img/products. Roughly 200x200px with white background.</p>'
			));
			echo $form->input('lead', array('label' => 'Lead (HTML)', 'default' => '<p>Enter the lead in HTML</p>'));
			echo $form->input('description', array('label' => 'Description (HTML)', 'default' => '<p>Enter a description in HTML</p>'));
			echo $form->input('price', array('label' => 'Price (n&hellip;nn.nn format)', 'default' => '0.00'));
			echo $form->input('Category', array('multiple' => 'checkbox', 'label' => __('Categories', true)));
			echo $form->input('Part', array('multiple' => 'checkbox', 'label' => __('Parts', true)));
		?>
	</fieldset>
	<?php echo $button->submit('Submit');?>
<?php echo $form->end();?>
</div>
