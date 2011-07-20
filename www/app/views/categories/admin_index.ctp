<div class="categories index">
<h2><?php __('Categories');?></h2>

<p>
<?php
echo $paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
));
?></p>

<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $paginator->sort('name');?></th>
	<th><?php echo $paginator->sort('modified');?></th>
	<th class="actions"><?php __('Actions');?></th>
</tr>
<?php foreach ($categories as $category): ?>
	<tr>
		<td>
			<?php echo $html->link($category['Category']['name'], array('action'=>'view', $category['Category']['slug'])); ?>
		</td>
		<td>
			<?php echo $category['Category']['modified']; ?>
		</td>
		<td class="actions">
			<?php echo $html->image('/img/icons/up.png', array('alt' => 'Move up', 'url' => array('action'=>'moveup', $category['Category']['id']))); ?>
			<?php echo $html->image('/img/icons/down.png', array('alt' => 'Move down', 'url' => array('action'=>'movedown', $category['Category']['id']))); ?>
			<?php echo $html->image('/img/icons/edit.png', array('alt' => 'Edit', 'url' => array('action'=>'edit', $category['Category']['id']))); ?>
			<?php echo $html->link($html->image('/img/icons/delete.png', array('alt' => 'Delete')), array('action'=>'delete', $category['Category']['id']), array('escape' => false), sprintf(__('Are you sure you want to delete %s?', true), $category['Category']['name'])); ?>
			<?php
				if (!$category['Category']['root']) {
					echo $html->image('/img/icons/home.png', array('alt' => 'Set as homepage', 'url' => array('action'=>'set_root', $category['Category']['id'])));
				}
			?>
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
		<li><?php echo $this->Html->link(__('New Category', true), array('action'=>'add')); ?></li>
		<li><?php echo $this->Html->link(__('Reset order', true), array('action'=>'reset_order')); ?></li>
	</ul>
</div>
