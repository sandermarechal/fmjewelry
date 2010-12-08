<div class="products index">
<h2><?php __('Products');?></h2>
<p>
<?php
echo $paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
));
?></p>
<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $paginator->sort('name');?></th>
	<th><?php echo $paginator->sort('price');?></th>
	<th><?php echo $paginator->sort('stock');?></th>
        <th>Owner</th>
	<th><?php echo $paginator->sort('created');?></th>
	<th><?php echo $paginator->sort('modified');?></th>
	<th class="actions"><?php __('Actions');?></th>
</tr>

<?php foreach ($products as $product): ?>
	<tr>
		<td>
			<?php echo $html->link($product['Product']['name'], array('action'=>'view', $product['Product']['id'])); ?>
		</td>
		<td>
			&euro;&nbsp;<?php echo $product['Product']['price']; ?>
		</td>
		<td>
			<?php echo $product['Product']['stock'] == STOCK_INFINITE ? 'infinite' : $product['Product']['stock']; ?>
		</td>
		<td>
			<?php echo $product['User']['name']; ?>
		</td>
		<td>
			<?php echo $product['Product']['created']; ?>
		</td>
		<td>
			<?php echo $product['Product']['modified']; ?>
		</td>
		<td class="actions">
                        <?php if ($Auth['User']['id'] == $product['User']['id']): ?>
                                <?php echo $html->image('/img/icons/edit.png', array('alt' => 'Edit', 'url' => array('action'=>'edit', $product['Product']['id']))); ?>
                                <?php echo $html->link($html->image('/img/icons/delete.png', array('alt' => 'Delete')), array('action'=>'delete', $product['Product']['id']), array('escape' => false), sprintf(__('Are you sure you want to delete %s?', true), $product['Product']['name'])); ?>
                        <?php endif; ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>
</div>

<div class="paging">
	<?php echo $paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));?>
 | 	<?php echo $paginator->numbers();?>
	<?php echo $paginator->next(__('next', true).' >>', array(), null, array('class'=>'disabled'));?>
</div>

<div class="actions">
	<ul>
		<li><?php echo $button->link(__('New Product', true), array('action'=>'add')); ?></li>
	</ul>
</div>
