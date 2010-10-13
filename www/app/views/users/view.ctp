<div class="users view">
	<h2>
		<?php if ($this->action == 'admin_view') {
			echo $user['User']['name'];
		} else {
			__('Your account');
		}
		?>
	</h2>
	<dl>
		<dt><?php __('Name'); ?></dt>
		<dd>
			<?php echo $user['User']['name']; ?>
			&nbsp;
		</dd>
		<dt><?php __('Email Address'); ?></dt>
		<dd>
			<?php echo $user['User']['email_address']; ?>
			&nbsp;
		</dd>
		<dt><?php __('Registered since'); ?></dt>
		<dd>
			<?php echo $user['User']['created']; ?>
			&nbsp;
		</dd>
	</dl>
</div>

<?php if ($this->action == 'admin_view'): ?>
	<div class="actions"><p>
		<?php echo $button->link(__('Edit account', true), array('action'=>'edit', $user['User']['id'])); ?>
	</p></div>
	<div class="related">
		<h3><?php printf(__('%s is a member of the following groups', true), $user['User']['name']);?></h3>

		<?php if (!empty($user['Group'])):?>
		<ul>
			<?php foreach ($user['Group'] as $group): ?>
			<li>
				<?php if ($this->action = 'admin_view') {
					echo $html->link($group['name'], array('controller' => 'groups', 'action' => 'view', $group['id']));
				} else { 
					echo $group['name'];
				}
				?>
			</li>
			<?php endforeach; ?>
		</ul>
		<?php endif; ?>

	</div>
<?php else: ?>
	<div class="actions"><p>
		<?php echo $button->link(__('Edit your account', true), array('action'=>'edit')); ?>
	</p></div>
<?php endif; ?>
