<script type="text/javascript">
	$(function() {
		$('#addressAdd input').change(function() {
			$('#address_new').attr('checked', 'checked');
		});

	});
</script>

<?php
	$javascript->link('jquery-1.3.2.min.js', false);

	if ($type == 'shipping') {
		echo $header->h2('Shipping address');
	} else {
		echo $header->h2('Billing address');
	}
?>

<?php echo $form->create('Address', array('url' => array('action' => 'select', $type)));?>

<?php if ($type == 'shipping'): ?>
	<p>Please select a shipping address or add a new address.</p>
<?php else: ?>
	<p>Please select billing address or add a new address. Your invoice will be e-mailed to
	<strong><?php echo $emailAddress; ?></strong> but it will be addressed to the billing address.</p>
<?php endif; ?>

<?php if ($addresses): ?>
	<fieldset id="addressSelect">
		<legend>Select an address</legend>
		<?php foreach ($addresses as $i => $address): ?>
			<input type="radio" name="data[address_id]"
				id="address_<?php echo $address['Address']['id'];?>"
				value="<?php echo $address['Address']['id'];?>"
				<?php
					if (!isset($this->data) && ($address['Address']['primary'] || $i == 0)) {
						echo 'checked="checked"';
					} 
				?>
				/>
			<label for="address_<?php echo $address['Address']['id'];?>">
				<?php echo $this->element('address', array('address' => $address)); ?>
			</label>
		<?php endforeach; ?>

	<input type="radio" name="data[address_id]" id="address_new" value="address_new" <?php if (isset($this->data)) { echo 'checked="checked"'; }?>/>
		<label for="address_new">A different address</label>
	</fieldset>

	<fieldset id="addressAdd">
		<legend>Use a different address</legend>
<?php else: ?>
	<fieldset id="addressAdd">
		<legend>Enter your address</legend>
<?php endif; ?>

	<?php
		echo $form->input('name');
		echo $form->input('address_1', array('label' => 'Address line 1'));
		echo $form->input('address_2', array('label' => 'Address line 2'));
		echo $form->input('postal_code');
		echo $form->input('city');
		echo $form->input('state');
		echo $form->input('country');
	?>
</fieldset>

<div class="submit">
	<?php echo $button->link('Back', '/cart/checkout/back'); ?>
	<?php echo $button->submit('Continue', array('div' => false));?>
</div>

</form>
