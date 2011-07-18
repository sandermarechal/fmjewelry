<script type="text/javascript">
	$(function() {
		base_price = <?php echo $product['Product']['price']; ?>;
		prices = <?php echo $javascript->object($prices); ?>;

		update_price = function() {
			price = base_price;
			$(':radio:checked').each(function() {
				price += prices[this.value];
			});
			price *= $('#quantity').val();
			$('#product-price').text(String(price.toFixed(2)));
			$('#product-price').format({format: "#,###.00", locale: "au"})
		}

		$(':radio').click(function() {
			update_price();
		});

		$('#quantity').change(function() {
			update_price();
		});

		update_price();
	});
</script>

<?php
	$javascript->link('jquery-1.3.2.min.js', false);
	$javascript->link('jquery.rich-array-min.js', false);
	$javascript->link('jquery.numberformatter-1.1.0.js', false);
	echo $this->element('control_box', $controlBox);
?>

<div id="product-view">
	<form method="post" action="/cart/add">
	<input type="hidden" name="data[Product][id]" value="<?php echo $product['Product']['id'];?>" />
	
	<div id="product-focus">
		<h3><?php echo $product['Product']['name'];?></h3>
		<?php
			if ($product['Product']['image']) {
				echo $html->image('/img/products/' . $product['Product']['image']);
			}
		?>
		<p>Price: $ <span id="product-price"><?php echo $product['Product']['price']; ?></span></p>
		<?php echo $button->submit('Add to cart', array('div' => false));?>
	</div>

	<h2><?php echo $product['Product']['name']; ?></h2>
	<?php echo $product['Product']['description']; ?>

	<p>Quantity: <input type="text" name="data[Product][quantity]" id="quantity" value="1" /></p>

	<?php if (!empty($product['Part'])): ?>
                <h3>Configure</h3>
		<div id="product-configure">
			<dl>
			<?php foreach ($product['Part'] as $part): ?>
				<dt><?php echo $part['label']; ?></dt>
				<dd>
					<?php foreach ($part['PartOption'] as $option): ?>
						<?php $checked = $option['default'] ? ' checked="checked"' : ''; ?>
						<input type="radio"
							id="<?php echo $option['id']?>"
							name="data[Part][<?php echo $part['id']?>]"
						        value="<?php echo $option['id']; ?>"
						       <?php echo $checked; ?>
						       />
						<label for="<?php echo $option['id']?>">
							<?php echo $option['name'] . ' ($ ' . $option['price'] . ')'; ?>
						</label>
						<br />
					<?php endforeach; ?>
				</dd>
			<?php endforeach; ?>
			</dl>
		</div>
	<?php endif; ?>

	<?php echo $button->submit('Add to cart'); ?>
	</form>
</div>
