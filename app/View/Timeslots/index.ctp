<div class="timeslots index">
	<h2><?php echo __('Schichtzeiten'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('start', 'Startzeit'); ?></th>
			<th><?php echo $this->Paginator->sort('end', 'Endezeit'); ?></th>
			<th class="actions"><?php echo __('Aktionen'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($timeslots as $timeslot): ?>
	<tr>
		<td><?php echo h($timeslot['Timeslot']['start']); ?>&nbsp;</td>
		<td><?php echo h($timeslot['Timeslot']['end']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Bearbeiten'), array('action' => 'edit', $timeslot['Timeslot']['id'])); ?>
			<?php echo $this->Form->postLink(__('Löschen'), array('action' => 'delete', $timeslot['Timeslot']['id']), array(), __('Möchtest du wirklich die Schichtzeit %s löschen?', $timeslot['Timeslot']['start'])); ?>
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
		<li><?php echo $this->Html->link(__('Neue Schicht'), array('action' => 'add')); ?></li>
	</ul>
</div>
