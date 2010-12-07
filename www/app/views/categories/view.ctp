<?php echo $this->element('control_box', $controlBox); ?>

<div id="category-view">
	<?php echo $header->h2($category['Category']['name']); ?>
	<?php echo $category['Category']['description']; ?>
</div>

<?php if ($subcategories): ?>
	<?php echo $header->h3('Browse'); ?>
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
			<?php echo $header->h3('Features'); ?>
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
