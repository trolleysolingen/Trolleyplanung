<div class="congregations index">
	<h2><?php echo __('Congregations'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('name', 'Name'); ?></th>
			<th class="actions"><?php echo __('Aktionen'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($congregations as $congregation): ?>
	<tr>
		<td><?php echo h($congregation['Congregation']['name']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Bearbeiten'), array('action' => 'edit', $congregation['Congregation']['id'])); ?>
			<?php echo $this->Form->postLink(__('Löschen'), array('action' => 'delete', $congregation['Congregation']['id']), array(), __('Are you sure you want to delete # %s?', $congregation['Congregation']['name'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</tbody>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Aktionen'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Neue Versammlung'), array('action' => 'add')); ?></li>
	</ul>
</div>