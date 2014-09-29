<div class="publishers form">
<?php echo $this->Form->create('Publisher'); ?>
	<fieldset>
		<legend><?php echo __('Edit Publisher'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('email');
		echo $this->Form->input('prename');
		echo $this->Form->input('surname');
		echo $this->Form->input('congregation_id');
		echo $this->Form->input('role_id');
		echo $this->Form->input('phone');
		echo $this->Form->input('mobile');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Publisher.id')), array(), __('Are you sure you want to delete # %s?', $this->Form->value('Publisher.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Publishers'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Congregations'), array('controller' => 'congregations', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Congregation'), array('controller' => 'congregations', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Roles'), array('controller' => 'roles', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Role'), array('controller' => 'roles', 'action' => 'add')); ?> </li>
	</ul>
</div>
