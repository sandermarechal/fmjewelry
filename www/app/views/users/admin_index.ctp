<div class="users index">
<h2><?php __('Users');?></h2>

<p><?php
echo $paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
));
?></p>

<table cellpadding="0" cellspacing="0">
	<tr>
		<th><?php echo $paginator->sort('name');?></th>
		<th><?php echo $paginator->sort('email_address');?></th>
		<th><?php echo $paginator->sort('active');?></th>
		<th><?php __('Member of'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>

	<?php foreach($users as $user): ?>
	<tr>
		<td>
			<?php echo $html->link($user['User']['name'], array('action'=>'view', $user['User']['id'])); ?>
		</td>
		<td>
			<?php echo $user['User']['email_address']; ?>
		</td>
		<td>
			<?php echo $user['User']['active'] ? 'Yes' : 'No'; ?>
		</td>
		<td>
			<?php
				foreach ($user['Group'] as $group) {
					echo $html->link($group['name'], array(
						'controller' => 'groups',
						'action' => 'view',
						$group['id'])
					) . "<br />\n";
				}
			?>
		</td>
		<td class="actions">
                        <?php echo $html->image('/img/icons/edit.png', array('alt' => 'Edit', 'url' => array('action'=>'edit', $user['User']['id']))); ?>
			<?php echo $html->link($html->image('/img/icons/delete.png', array('alt' => 'Delete')), array(
				'action'=>'delete',
				$user['User']['id']),
				array('escape' => false),
				sprintf(__('Are you sure you want to delete # %s?', true),
				$user['User']['id']));
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
		<li><?php echo $this->Html->link(__('New User', true), array('action'=>'add')); ?></li>
	</ul>
</div>
