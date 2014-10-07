<div class="publishers form">
<?php echo $this->Form->create('Publisher'); ?>
	<fieldset>
		<legend><?php echo __('Verkündiger bearbeiten'); ?></legend>
	<?php
		echo $this->Form->input('email');
		echo $this->Form->input('prename');
		echo $this->Form->input('surname');
		echo $this->Form->input('congregation_id', array(
			'type' => 'hidden',
			'value' => $publisher['Congregation']['id']
		));
		echo $this->Form->input('role_id');
		echo $this->Form->input('phone');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>

