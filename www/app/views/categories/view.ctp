<?php echo $this->element('control_box', $controlBox); ?>

<div id="category-view">
	<h2><?php echo $category['Category']['name']; ?></h2>
	<?php echo $category['Category']['description']; ?>
</div>

<?php if ($subcategories): ?>
	<h3>Browse</h3>
	<div id="subcategories">
		<ul>
			<?php foreach ($subcategories as $index => $subcategory): ?>
				<li <?php if ($index % 2 == 0) { echo 'class="left"'; } ?>>
					<a href="/categories/view/<?php echo $subcategory['Category']['slug']; ?>">
					<?php if ($subcategory['Category']['image']): ?>
					<img src="/img/categories/<?php echo $subcategory['Category']['image'];?>" alt="<?php echo $subcategory['Category']['name'];?> icon" />
					<?php endif; ?>
					<?php echo $subcategory['Category']['name']; ?>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
		<br class="clearer" />
	</div>
<?php endif; ?>

<?php if ($products): ?>
	<div id="products">
		<?php if ($subcategories): ?>
                        <h3>Features</h3>
		<?php endif; ?>
		<div class="product-group">
		<?php foreach ($products as $i => $product): ?>
			<div class="product-box<?php if (($i + 1) % 3 == 0) { echo ' last'; } ?>">
				<h4><?php echo $product['Product']['name']; ?></h4>
				<?php
					if ($product['Product']['image']) {
						echo $html->image('/img/products/' . $product['Product']['image']);
					}
					echo $product['Product']['lead'];
					echo $button->link('Configure', array('controller' => 'products', 'action' => 'view', $product['Product']['slug']));
				?>
			</div>
		<?php endforeach; ?>
		</div>
	</div>
	<!-- <br class="clearer" /> -->
<?php endif; ?>
