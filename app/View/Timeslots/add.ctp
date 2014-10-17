<div class="timeslots form">
	<?php echo $this->Form->create('Timeslot'); ?>
	<fieldset>
		<legend><?php echo __('Schicht anlegen '); ?></legend>
		<?php
		echo $this->Form->input('congregation_id', array(
			'type' => 'hidden',
			'value' => $publisher['Congregation']['id']
		));
		?>
		<div class="form-group">
			<label for="start" class="col-sm-2 control-label">Start:</label>
			<?php
			echo $this->Form->input('start', array('label'=>false, 'class' => 'form-control', 'placeholder' => 'HH:MM', 'style' => 'width:250px', 'id' => 'start'));
			?>
		</div>
		<div class="form-group">
			<label for="end" class="col-sm-2 control-label">Ende:</label>
			<?php
			echo $this->Form->input('end', array('label'=>false, 'class' => 'form-control', 'placeholder' => 'HH:MM', 'style' => 'width:250px', 'id' => 'end'));
			?>
		</div>

	</fieldset>

	<?php echo $this->Form->end(array('label' => 'Speichern', 'class' => 'btn btn-primary')); ?>
</div>