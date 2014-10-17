<div class="timeslots form">
<?php echo $this->Form->create('Timeslot'); ?>
	<fieldset>
		<legend><?php echo __('Schicht bearbeiten'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('congregation_id', array(
			'type' => 'hidden',
			'value' => $publisher['Congregation']['id']
		));
	?>
	<div class="form-group">
		<label for="start" class="col-sm-2 control-label">Start:</label>
		<?php
		echo $this->Form->input('start', array('label'=>false, 'class' => 'form-control', 'placeholder' => 'Startzeit', 'style' => 'width:250px', 'id' => 'start'));
		?>
	</div>
	<div class="form-group">
		<label for="end" class="col-sm-2 control-label">Ende:</label>
		<?php
		echo $this->Form->input('end', array('label'=>false, 'class' => 'form-control', 'placeholder' => 'Endzeit', 'style' => 'width:250px', 'id' => 'end'));
		?>
	</div>

	</fieldset>

	<?php echo $this->Form->end(array('label' => 'Speichern', 'class' => 'btn btn-primary')); ?>
</div>