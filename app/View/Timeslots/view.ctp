<div class="timeslots view">
<h2><?php echo __('Timeslot'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($timeslot['Timeslot']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Start'); ?></dt>
		<dd>
			<?php echo h($timeslot['Timeslot']['start']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('End'); ?></dt>
		<dd>
			<?php echo h($timeslot['Timeslot']['end']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Timeslot'), array('action' => 'edit', $timeslot['Timeslot']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Timeslot'), array('action' => 'delete', $timeslot['Timeslot']['id']), array(), __('Are you sure you want to delete # %s?', $timeslot['Timeslot']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Timeslots'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Timeslot'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Reservations'), array('controller' => 'reservations', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Reservation'), array('controller' => 'reservations', 'action' => 'add')); ?> </li>
	</ul>
</div>
