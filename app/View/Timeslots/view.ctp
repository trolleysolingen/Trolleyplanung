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
<div class="related">
	<h3><?php echo __('Related Reservations'); ?></h3>
	<?php if (!empty($timeslot['Reservation'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Congregation Id'); ?></th>
		<th><?php echo __('Day'); ?></th>
		<th><?php echo __('Timeslot Id'); ?></th>
		<th><?php echo __('Publisher1 Id'); ?></th>
		<th><?php echo __('Publisher2 Id'); ?></th>
		<th><?php echo __('Guestname'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($timeslot['Reservation'] as $reservation): ?>
		<tr>
			<td><?php echo $reservation['id']; ?></td>
			<td><?php echo $reservation['congregation_id']; ?></td>
			<td><?php echo $reservation['day']; ?></td>
			<td><?php echo $reservation['timeslot_id']; ?></td>
			<td><?php echo $reservation['publisher1_id']; ?></td>
			<td><?php echo $reservation['publisher2_id']; ?></td>
			<td><?php echo $reservation['guestname']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'reservations', 'action' => 'view', $reservation['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'reservations', 'action' => 'edit', $reservation['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'reservations', 'action' => 'delete', $reservation['id']), array(), __('Are you sure you want to delete # %s?', $reservation['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Reservation'), array('controller' => 'reservations', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
