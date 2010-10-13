<div class="groups view">
<h2><?php  __('Group');?></h2>
	<dl>
		<dt><?php __('Name'); ?></dt>
		<dd>
			<?php echo $group['Group']['name']; ?>
			&nbsp;
		</dd>
		<dt><?php __('Default'); ?></dt>
		<dd>
			<?php echo $group['Group']['default']
				? 'Yes, user are automatically added to this group.'
				: 'No, users are not automatically added to this group'; ?>
			&nbsp;
		</dd>
		<dt><?php __('Created'); ?></dt>
		<dd>
			<?php echo $group['Group']['created']; ?>
			&nbsp;
		</dd>
		<dt><?php __('Modified'); ?></dt>
		<dd>
			<?php echo $group['Group']['modified']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<ul>
		<li><?php echo $button->link(__('Edit Group', true), array('action'=>'edit', $group['Group']['id'])); ?> </li>
		<li><?php echo $button->link(__('Delete Group', true), array('action'=>'delete', $group['Group']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $group['Group']['id'])); ?> </li>
	</ul>
</div>

<div class="related">
	<h3><?php __('Permissions');?></h3>
	<?php if (!empty($group['Permission'])):?>
		<table cellpadding = "0" cellspacing = "0">
		<tr>
			<th><?php __('Permission'); ?></th>
			<th><?php __('Created'); ?></th>
			<th><?php __('Modified'); ?></th>
			<th class="actions"><?php __('Actions');?></th>
		</tr>
		<?php foreach ($group['Permission'] as $permission):?>
			<tr>
				<td><?php echo $permission['name'];?></td>
				<td><?php echo $permission['created'];?></td>
				<td><?php echo $permission['modified'];?></td>
				<td class="actions">
					<?php echo $html->link(__('Delete', true), array('controller'=> 'permissions', 'action'=>'delete', $permission['id']), null, sprintf(__('Are you sure you want to delete permission %s?', true), $permission['name'])); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>
	<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $button->link(__('New Permission', true), array('controller'=> 'permissions', 'action'=>'add', $group['Group']['id']));?> </li>
		</ul>
	</div>
</div>

<div class="related">
	<h3><?php __('Members');?></h3>
	<?php if (!empty($group['User'])):?>
		<table cellpadding = "0" cellspacing = "0">
		<tr>
			<th><?php __('Name'); ?></th>
			<th><?php __('Email Address'); ?></th>
			<th><?php __('Created'); ?></th>
			<th><?php __('Modified'); ?></th>
			<th class="actions"><?php __('Actions');?></th>
		</tr>
		<?php foreach ($group['User'] as $user): ?>
			<tr>
				<td><?php echo $html->link($user['name'], array('controller'=> 'users', 'action'=>'view', $user['id'])); ?></td>
				<td><?php echo $user['email_address'];?></td>
				<td><?php echo $user['created'];?></td>
				<td><?php echo $user['modified'];?></td>
				<td class="actions">
					<?php echo $html->link(__('Remove from group', true), array('action'=>'removeMember', $group['Group']['id'], $user['id']), null, sprintf(__('Are you sure you want to remove %s from this group?', true), $user['name'])); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>
	<?php endif; ?>
</div>
