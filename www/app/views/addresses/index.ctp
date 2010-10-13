<div class="addresses index">
<h2><?php 
	if ($this->action == 'admin_index') {
		echo sprintf(__('Addresses for %s &lt;%s&gt;', true), $user['User']['name'], $user['User']['email_address']);
	} else {
		__('Your addresses');
	}
?></h2>

<table cellpadding="0" cellspacing="0">
<?php foreach ($addresses as $address): ?>
	<tr<?php if ($address['Address']['primary']) {echo ' class="primary"'; }?>>
		<td>
		<?php if ($address['Address']['primary']) {echo '*'; }?>
		<?php echo $address['Address']['name'];?><br />
		<?php echo $address['Address']['address_1'];?><br />
		<?php ife($address['Address']['address_2'], $address['Address']['address_2'] . '<br />', '');?>
		<?php echo $address['Address']['postal_code']; ?>
		<?php echo $address['Address']['city']; ?><br />
		<?php ife($address['Address']['state'], $address['Address']['state'] . '<br />', '');?>
		<?php echo strtoupper($address['Address']['country']); ?>
		</td>
		<td class="actions">
			<?php echo $html->link(__('Edit', true), array('action'=>'edit', $address['Address']['id'])); ?>
			<?php echo $html->link(__('Delete', true), array('action'=>'delete', $address['Address']['id']), null, sprintf(__('Are you sure you want to delete this address?', true), $address['Address']['id'])); ?>
			<?php
				if (!$address['Address']['primary']) {
					echo $html->link(__('Make primary', true), array('action' => 'primary', $address['Address']['id']));
				}
			?>
		</td>
	</tr>
<?php endforeach; ?>
</table>

<?php if (!$addresses): ?>
	<p>You have not created any addresses yet. Click the link below to add a new address.</p>
<?php endif; ?>

</div>

<div class="actions">
	<ul>
		<li>
			<?php
			if ($this->action == 'admin_index') {
				echo $button->link(__('New Address', true), array('action' => 'add', $user['User']['id']));
			} else {
				echo $button->link(__('New Address', true), array('action' => 'add'));
			}
			?>
		</li>
	</ul>
</div>
