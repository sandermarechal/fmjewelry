<div class="adminIndex">

<?php
	if     ($filter == 'pending') echo '<h2>Pending orders</h2>';
	elseif ($filter == 'all') echo '<h2>All orders</h2>';
	else   echo '<h2>Orders for ' . $user['User']['email_address'] . '</h2>';
?>

<div class="actions">
	<ul>
		<li><?php echo $this->Html->link('All', array('all'));?></li>
		<li><?php echo $this->Html->link('Pending', array('pending'));?></li>
	</ul>
</div>

<p>
<?php echo $paginator->counter(array(
	'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)));
?>
</p>

<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $paginator->sort('Order', 'id');?></th>
	<th><?php echo $paginator->sort('user_id');?></th>
	<th><?php echo $paginator->sort('price');?></th>
	<th><?php echo $paginator->sort('Submitted', 'created');?></th>
	<th><?php echo $paginator->sort('invoiced');?></th>
	<th><?php echo $paginator->sort('paid');?></th>
	<th><?php echo $paginator->sort('shipped');?></th>
	<th class="actions"><?php __('Actions');?></th>
</tr>
<?php foreach ($orders as $order): ?>
	<tr>
		<td>
			<?php echo $html->link(sprintf('%06d', $order['Order']['id']), array('action'=>'view', $order['Order']['id'])); ?>
		</td>
		<td>
			<?php echo $html->link($order['User']['email_address'], array('controller'=> 'users', 'action'=>'view', $order['User']['id'])); ?>
		</td>
		<td class="price">
			<span class="currency">$</span><?php echo number_format($order['Order']['price'], 2); ?>
		</td>
		<td>
			<?php echo $order['Order']['created'] == '0000-00-00 00:00:00' ? '-' : substr($order['Order']['created'], 0, 10); ?>
		</td>
		<td>
			<?php echo $order['Order']['invoiced'] == '0000-00-00 00:00:00' ? '-' : substr($order['Order']['invoiced'], 0, 10); ?>
		</td>
		<td>
			<?php echo $order['Order']['paid'] == '0000-00-00 00:00:00' ? '-' : substr($order['Order']['paid'], 0, 10); ?>
		</td>
		<td>
			<?php echo $order['Order']['shipped'] == '0000-00-00 00:00:00' ? '-' : substr($order['Order']['shipped'], 0, 10); ?>
		</td>
		<td class="actions">
			<?php echo $html->link(__('Edit', true), array('action'=>'edit', $order['Order']['id'])); ?>
			<?php echo $html->link(__('Delete', true), array('action'=>'delete', $order['Order']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $order['Order']['id'])); ?>
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
