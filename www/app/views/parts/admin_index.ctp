<div class="parts index">
<h2><?php __('Parts');?></h2>
<p>
<?php
echo $paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
));
?></p>
<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $paginator->sort('name');?></th>
	<th><?php echo $paginator->sort('label');?></th>
	<th><?php echo $paginator->sort('created');?></th>
	<th><?php echo $paginator->sort('modified');?></th>
	<th class="actions"><?php __('Actions');?></th>
</tr>
<?php foreach ($parts as $part): ?>
	<tr>
		<td>
			<?php echo $html->link($part['Part']['name'], array('action'=>'view', $part['Part']['id'])); ?>
		</td>
		<td>
			<?php echo $part['Part']['label']; ?>
		</td>
		<td>
			<?php echo $part['Part']['created']; ?>
		</td>
		<td>
			<?php echo $part['Part']['modified']; ?>
		</td>
		<td class="actions">
			<?php echo $html->link(__('Edit', true), array('action'=>'edit', $part['Part']['id'])); ?>
			<?php echo $html->link(__('Delete', true), array('action'=>'delete', $part['Part']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $part['Part']['id'])); ?>
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
		<li><?php echo $button->link(__('New Part', true), array('action'=>'add')); ?></li>
	</ul>
</div>
