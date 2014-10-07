<div class="timeslots form">
<?php echo $this->Form->create('Timeslot'); ?>
	<fieldset>
		<legend><?php echo __('Add Timeslot'); ?></legend>
	<?php
		echo $this->Form->input('start');
		echo $this->Form->input('end');
		echo $this->Form->input('congregation_id', array(
			'type' => 'hidden',
			'value' => $publisher['Congregation']['id']
		));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
