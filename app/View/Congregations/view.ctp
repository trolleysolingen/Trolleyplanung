<div class="congregations view">
<h2><?php echo __('Congregation'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($congregation['Congregation']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($congregation['Congregation']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Path'); ?></dt>
		<dd>
			<?php echo h($congregation['Congregation']['path']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Congregation'), array('action' => 'edit', $congregation['Congregation']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Congregation'), array('action' => 'delete', $congregation['Congregation']['id']), array(), __('Are you sure you want to delete # %s?', $congregation['Congregation']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Congregations'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Congregation'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Publishers'), array('controller' => 'publishers', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Publisher'), array('controller' => 'publishers', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Reservations'), array('controller' => 'reservations', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Reservation'), array('controller' => 'reservations', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Publishers'); ?></h3>
	<?php if (!empty($congregation['Publisher'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Email'); ?></th>
		<th><?php echo __('Prename'); ?></th>
		<th><?php echo __('Surname'); ?></th>
		<th><?php echo __('Congregation Id'); ?></th>
		<th><?php echo __('Role Id'); ?></th>
		<th><?php echo __('Phone'); ?></th>
		<th><?php echo __('Mobile'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($congregation['Publisher'] as $publisher): ?>
		<tr>
			<td><?php echo $publisher['id']; ?></td>
			<td><?php echo $publisher['email']; ?></td>
			<td><?php echo $publisher['prename']; ?></td>
			<td><?php echo $publisher['surname']; ?></td>
			<td><?php echo $publisher['congregation_id']; ?></td>
			<td><?php echo $publisher['role_id']; ?></td>
			<td><?php echo $publisher['phone']; ?></td>
			<td><?php echo $publisher['mobile']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'publishers', 'action' => 'view', $publisher['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'publishers', 'action' => 'edit', $publisher['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'publishers', 'action' => 'delete', $publisher['id']), array(), __('Are you sure you want to delete # %s?', $publisher['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Publisher'), array('controller' => 'publishers', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php echo __('Related Reservations'); ?></h3>
	<?php if (!empty($congregation['Reservation'])): ?>
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
	<?php foreach ($congregation['Reservation'] as $reservation): ?>
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
