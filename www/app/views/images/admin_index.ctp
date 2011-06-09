<div class="images index">
	<h2><?php __('Your images');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('filename');?></th>
			<th>Thumbnail</th>
			<th><?php echo $this->Paginator->sort('Uploaded', 'created');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
        <?php foreach ($images as $image): ?>
	<tr>
		<td><?php echo $image['Image']['filename']; ?>&nbsp;</td>
                <td><img src="<?php echo $image['Image']['thumb']; ?>" /></td>
		<td><?php echo $image['Image']['created']; ?>&nbsp;</td>
		<td class="actions">
                        <?php echo $html->link($html->image('/img/icons/delete.png', array('alt' => 'Delete')), array('action'=>'delete', $image['Image']['id']), array('escape' => false), sprintf(__('Are you sure you want to delete %s?', true), $image['Image']['filename'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
	));
	?>	</p>

	<div class="paging">
		<?php echo $this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class'=>'disabled'));?>
	 | 	<?php echo $this->Paginator->numbers();?>
 |
		<?php echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled'));?>
	</div>
</div>
<div class="upload" style="clear: both;">
	<h3>Add an image</h3>
	<?php echo $this->Form->create(false, $options = array('type' => 'file', 'action' => 'upload')); ?>
	<fieldset>
		<?php echo $this->Form->file('image'); ?>
	</fieldset>
	<?php echo $button->submit('Add');?>
	<?php echo $form->end(); ?>
</div>
