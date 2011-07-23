<?php
	$html->css('wmd', 'stylesheet', array('inline' => false));
	$html->script('wmd', array('inline' => false));
	$html->script('showdown', array('inline' => false));
?>

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
			echo $form->input('lead', array('label' => 'Lead (text)', 'default' => 'Enter a short lead text'));
			echo $form->input('price', array('label' => 'Price (n&hellip;nn.nn format)', 'default' => '0.00'));
			echo $form->input('stock', array('label' => 'Stock (-1 for infinite a.k.a. made-to-order )', 'default' => 0));
			echo $form->input('Category', array('multiple' => 'checkbox', 'label' => __('Categories', true)));
		?>
	</fieldset>
    <fieldset>
        <legend>Description</legend>
        <div id="wmd-button-bar"></div>
		<?php echo $form->input('description', array('label' => false, 'id' => 'wmd-input', 'default' => 'Enter a description')); ?>
        <div id="wmd-preview" class="polaroid"></div>
    </fieldset>
<?php echo $form->end('Save');?>
</div>
