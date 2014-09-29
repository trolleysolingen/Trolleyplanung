<div class="roles view">
<h2><?php echo __('Role'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($role['Role']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($role['Role']['name']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Role'), array('action' => 'edit', $role['Role']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Role'), array('action' => 'delete', $role['Role']['id']), array(), __('Are you sure you want to delete # %s?', $role['Role']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Roles'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Role'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Publishers'), array('controller' => 'publishers', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Publisher'), array('controller' => 'publishers', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Publishers'); ?></h3>
	<?php if (!empty($role['Publisher'])): ?>
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
	<?php foreach ($role['Publisher'] as $publisher): ?>
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
