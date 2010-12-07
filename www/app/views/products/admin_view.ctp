<div class="products view">
<h2><?php  __('Product');?></h2>
	<dl>
		<dt><?php __('Name'); ?></dt>
		<dd>
			<?php echo $product['Product']['name']; ?>
			&nbsp;
		</dd>
		<dt><?php __('Lead'); ?></dt>
		<dd>
			<?php echo h($product['Product']['lead']); ?>
			&nbsp;
		</dd>
		<dt><?php __('Description'); ?></dt>
		<dd>
			<?php echo h($product['Product']['description']); ?>
			&nbsp;
		</dd>
		<dt><?php __('Image'); ?></dt>
		<dd>
			<?php echo $html->image('/img/products/' . $product['Product']['image']); ?>
			&nbsp;
		</dd>
		<dt><?php __('Base price'); ?></dt>
		<dd>$ <?php echo $product['Product']['price']; ?> (without options)</dd>
		<dt><?php __('Default price'); ?></dt>
		<dd>$ <?php echo $product['Product']['price_default']; ?> (including default options)</dd>
		<dt><?php __('Minimum price'); ?></dt>
		<dd>$ <?php echo $product['Product']['price_min']; ?> (including cheapest options)</dd>
		<dt><?php __('Owner'); ?></dt>
		<dd><?php echo $product['User']['name']; ?>&nbsp;</dd>
		<dt><?php __('Created'); ?></dt>
		<dd>
			<?php echo $product['Product']['created']; ?>
			&nbsp;
		</dd>
		<dt><?php __('Modified'); ?></dt>
		<dd>
			<?php echo $product['Product']['modified']; ?>
			&nbsp;
		</dd>
		<dt><?php __('Categories'); ?></dt>
		<dd>
			<?php
				if ($product['Category']) {
					foreach ($product['Category'] as $category) {
						echo $html->link($category['name'], array('controller' => 'categories', 'action' => 'view', $category['slug'])) . ' ';
					}
				}
			?>
		</dd>
	</dl>
</div>
<?php if ($Auth['User']['id'] == $product['User']['id']): ?>
        <div class="actions">
                <ul>
                        <li><?php echo $button->link(__('Edit Product', true), array('action'=>'edit', $product['Product']['id'])); ?> </li>
                        <li><?php echo $button->link(__('Delete Product', true), array('action'=>'delete', $product['Product']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $product['Product']['id'])); ?> </li>
                </ul>
        </div>
<?php endif; ?>

<div class="related">
	<h3><?php __('Related Parts');?></h3>
	<?php if (!empty($product['Part'])):?>
	<dl>
		<?php foreach ($product['Part'] as $part): ?>
		<dt><?php echo $html->link($part['name'], array('controller'=> 'parts', 'action'=>'view', $part['id'])); ?></dt>
		<dd style="margin-left: 20em;">
			<ul>
			<?php foreach ($part['PartOption'] as $option): ?>
				<li>
				<?php
					if ($option['default']) {
						echo '* ';
					}
					echo $option['name'] . '($ ' . $option['price'] . ')';
				?>
			<?php endforeach; ?>
			</ul>
		</dd>
		<?php endforeach; ?>
	</dl>
	<?php endif; ?>
</div>
