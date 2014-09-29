<div class="publishers view">
<h2><?php echo __('Publisher'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($publisher['Publisher']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Email'); ?></dt>
		<dd>
			<?php echo h($publisher['Publisher']['email']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Prename'); ?></dt>
		<dd>
			<?php echo h($publisher['Publisher']['prename']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Surname'); ?></dt>
		<dd>
			<?php echo h($publisher['Publisher']['surname']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Congregation'); ?></dt>
		<dd>
			<?php echo $this->Html->link($publisher['Congregation']['name'], array('controller' => 'congregations', 'action' => 'view', $publisher['Congregation']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Role'); ?></dt>
		<dd>
			<?php echo $this->Html->link($publisher['Role']['name'], array('controller' => 'roles', 'action' => 'view', $publisher['Role']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Phone'); ?></dt>
		<dd>
			<?php echo h($publisher['Publisher']['phone']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Mobile'); ?></dt>
		<dd>
			<?php echo h($publisher['Publisher']['mobile']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Publisher'), array('action' => 'edit', $publisher['Publisher']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Publisher'), array('action' => 'delete', $publisher['Publisher']['id']), array(), __('Are you sure you want to delete # %s?', $publisher['Publisher']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Publishers'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Publisher'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Congregations'), array('controller' => 'congregations', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Congregation'), array('controller' => 'congregations', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Roles'), array('controller' => 'roles', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Role'), array('controller' => 'roles', 'action' => 'add')); ?> </li>
	</ul>
</div>
