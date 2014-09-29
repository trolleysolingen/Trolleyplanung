<div class="publishers index">
	<h2><?php echo __('Publishers'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('email'); ?></th>
			<th><?php echo $this->Paginator->sort('prename'); ?></th>
			<th><?php echo $this->Paginator->sort('surname'); ?></th>
			<th><?php echo $this->Paginator->sort('congregation_id'); ?></th>
			<th><?php echo $this->Paginator->sort('role_id'); ?></th>
			<th><?php echo $this->Paginator->sort('phone'); ?></th>
			<th><?php echo $this->Paginator->sort('mobile'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($publishers as $publisher): ?>
	<tr>
		<td><?php echo h($publisher['Publisher']['id']); ?>&nbsp;</td>
		<td><?php echo h($publisher['Publisher']['email']); ?>&nbsp;</td>
		<td><?php echo h($publisher['Publisher']['prename']); ?>&nbsp;</td>
		<td><?php echo h($publisher['Publisher']['surname']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($publisher['Congregation']['name'], array('controller' => 'congregations', 'action' => 'view', $publisher['Congregation']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($publisher['Role']['name'], array('controller' => 'roles', 'action' => 'view', $publisher['Role']['id'])); ?>
		</td>
		<td><?php echo h($publisher['Publisher']['phone']); ?>&nbsp;</td>
		<td><?php echo h($publisher['Publisher']['mobile']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $publisher['Publisher']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $publisher['Publisher']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $publisher['Publisher']['id']), array(), __('Are you sure you want to delete # %s?', $publisher['Publisher']['id'])); ?>
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
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Publisher'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Congregations'), array('controller' => 'congregations', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Congregation'), array('controller' => 'congregations', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Roles'), array('controller' => 'roles', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Role'), array('controller' => 'roles', 'action' => 'add')); ?> </li>
	</ul>
</div>
