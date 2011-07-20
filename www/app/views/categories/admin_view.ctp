<div class="categories view">
<h2><?php  __('Category');?></h2>
	<dl>
		<dt><?php __('Name'); ?></dt>
		<dd>
			<?php echo $category['Category']['name']; ?>
			&nbsp;
		</dd>
		<dt><?php __('Slug'); ?></dt>
		<dd>
			<?php echo $category['Category']['slug']; ?>
			&nbsp;
		</dd>
		<dt><?php __('Description'); ?></dt>
		<dd>
			<?php echo h($category['Category']['description']); ?>
			&nbsp;
		</dd>
		<dt><?php __('Image'); ?></dt>
		<dd>
			<?php echo $category['Category']['image']; ?>
			&nbsp;
		</dd>
		<dt><?php __('Created'); ?></dt>
		<dd>
			<?php echo $category['Category']['created']; ?>
			&nbsp;
		</dd>
		<dt><?php __('Modified'); ?></dt>
		<dd>
			<?php echo $category['Category']['modified']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<ul>
		<li><?php echo $this->Html->link(__('Edit Category', true), array('action'=>'edit', $category['Category']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Category', true), array('action'=>'delete', $category['Category']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $category['Category']['id'])); ?> </li>
	</ul>
</div>

<h3><?php  __('Products in this category');?></h3>
<table cellpadding="0" cellspacing="0">
<tr>
	<th>Product</th>
	<th>Actions</th>
</tr>
<?php foreach ($category['Product'] as $product): ?>
	<tr>
		<td><?php echo $html->link($product['name'], array('controller' => 'products', 'action' => 'view', $product['id'])); ?></td>
		<td>
			<?php echo $html->image('/img/icons/up.png', array('alt' => 'Move up', 'url' => array('action' => 'product_moveup', $product['CategoriesProduct']['id']))); ?>
			<?php echo $html->image('/img/icons/down.png', array('alt' => 'Move down', 'url' => array('action' => 'product_movedown', $product['CategoriesProduct']['id']))); ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>
<div class="actions">
	<ul>
		<li><?php echo $this->Html->link(__('Reset order', true), array('action'=>'product_reset_order', $category['Category']['slug'])); ?> </li>
	</ul>
</div>
