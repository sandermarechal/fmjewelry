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
		<dt><?php __('Price'); ?></dt>
		<dd>&euro; <?php echo $product['Product']['price']; ?></dd>
		<dt><?php __('Stock'); ?></dt>
		<dd><?php echo $product['Product']['stock'] == STOCK_INFINITE ? 'infinite' : $product['Product']['stock']; ?></dd>
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
		<dd>&nbsp;
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
