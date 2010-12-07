<div class="parts view">
<h2><?php  __('Part');?></h2>
	<dl>
		<dt><?php __('Name'); ?></dt>
		<dd>
			<?php echo $part['Part']['name']; ?>
			&nbsp;
		</dd>
		<dt><?php __('Label'); ?></dt>
		<dd>
			<?php echo $part['Part']['label']; ?>
			&nbsp;
		</dd>
		<dt><?php __('Created'); ?></dt>
		<dd>
			<?php echo $part['Part']['created']; ?>
			&nbsp;
		</dd>
		<dt><?php __('Modified'); ?></dt>
		<dd>
			<?php echo $part['Part']['modified']; ?>
			&nbsp;
		</dd>
		<dt><?php __('Products'); ?></dt>
		<dd>
			<?php
				if ($part['Product']) {
					foreach ($part['Product'] as $product) {
						echo $html->link($product['name'], array('controller' => 'products', 'action' => 'view', $product['id'])) . ' ';
					}
				}
			?>
		</dd>
	</dl>
</div>
<div class="actions">
	<ul>
		<li><?php echo $button->link(__('Edit Part', true), array('action'=>'edit', $part['Part']['id'])); ?> </li>
		<li><?php echo $button->link(__('Delete Part', true), array('action'=>'delete', $part['Part']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $part['Part']['id'])); ?> </li>
	</ul>
</div>

<div class="related">
	<h3><?php __('Related Part Options');?></h3>
	<?php if (!empty($part['PartOption'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Name'); ?></th>
		<th><?php __('Price'); ?></th>
		<th><?php __('Default'); ?></th>
		<th><?php __('Created'); ?></th>
		<th><?php __('Modified'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php foreach ($part['PartOption'] as $partOption): ?>
		<tr>
			<td><?php echo $partOption['name'];?></td>
			<td><?php echo $partOption['price'];?></td>
			<td><?php if ($partOption['default']) echo '*';?>&nbsp;</td>
			<td><?php echo $partOption['created'];?></td>
			<td><?php echo $partOption['modified'];?></td>
			<td class="actions">
				<?php echo $html->link(__('Delete', true), array('controller'=> 'part_options', 'action'=>'delete', $partOption['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $partOption['id'])); ?>
				<?php
					if (!$partOption['default']) {
						echo $html->link(__('Set default', true), array('controller'=> 'part_options', 'action'=>'set_default', $partOption['id']));
					}
				?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>
</div>

<div class="partOptions form">
<?php echo $form->create('PartOption', array('url' => array('action' => 'add', $part['Part']['id'])));?>
	<fieldset>
 		<legend><?php __('Add PartOption');?></legend>
		<?php
			echo $form->input('name');
			echo $form->input('price', array('label' => 'Price (n&hellip;nn.nn format)', 'default' => '0.00'));
		?>
	</fieldset>
	<?php echo $button->submit('Submit');?>
<?php echo $form->end();?>
</div>
