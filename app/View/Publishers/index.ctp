<div class="publishers index">
	<h2><?php echo __('Verkündiger der Versammlung ' . $publisher['Congregation']['name']); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('email', 'Email'); ?></th>
			<th><?php echo $this->Paginator->sort('prename', 'Vorname'); ?></th>
			<th><?php echo $this->Paginator->sort('surname', 'Nachname'); ?></th>
			<th><?php echo $this->Paginator->sort('role_id', 'Rolle'); ?></th>
			<th><?php echo $this->Paginator->sort('phone', 'Telefon'); ?></th>
			<th class="actions"><?php echo __('Aktionen'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($publishers as $publisher): ?>
	<tr>
		<td><?php echo h($publisher['Publisher']['email']); ?>&nbsp;</td>
		<td><?php echo h($publisher['Publisher']['prename']); ?>&nbsp;</td>
		<td><?php echo h($publisher['Publisher']['surname']); ?>&nbsp;</td>
		<td>
			<?php echo h($publisher['Role']['name']); ?>
		</td>
		<td><?php echo h($publisher['Publisher']['phone']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Bearbeiten'), array('action' => 'edit', $publisher['Publisher']['id'])); ?>
			<?php echo $this->Form->postLink(__('Löschen'), array('action' => 'delete', $publisher['Publisher']['id']), array(), __('Bist du sicher, dass du %s löschen möchtest?', $publisher['Publisher']['surname'] . ' ' .$publisher['Publisher']['surname'])); ?>
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
		<li><?php echo $this->Html->link(__('Neuer Verkündiger'), array('action' => 'add')); ?></li>
	</ul>
</div>