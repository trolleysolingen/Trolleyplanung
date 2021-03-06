<div class="timeslots form">
<?php echo $this->Form->create('Timeslot', array('class' => 'form-horizontal')); ?>
	<fieldset>
		<legend><?php echo __('Schicht bearbeiten'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('congregation_id', array(
			'type' => 'hidden',
			'value' => $publisher['Congregation']['id']
		));
		echo $this->Form->input('route_id', array('type' => 'hidden'));
		echo $this->Form->input('day', array('type' => 'hidden'));
	?>
	<div class="form-group">
		<div class="col-sm-1 col-md-2"></div>
		<label for="start" class="col-sm-2 control-label">Start:</label>
		<div class="col-sm-8 col-md-6">
			<?php
			echo $this->Form->input('start', array('label'=>false, 'class' => 'form-control', 'placeholder' => 'Startzeit', 'style' => 'width:250px', 'id' => 'start'));
			?>
		</div>
		<div class="col-sm-1 col-md-2"></div>
	</div>
	<div class="form-group">
		<div class="col-sm-1 col-md-2"></div>
		<label for="end" class="col-sm-2 control-label">Ende:</label>
		<div class="col-sm-8 col-md-6">
			<?php
			echo $this->Form->input('end', array('label'=>false, 'class' => 'form-control', 'placeholder' => 'Endzeit', 'style' => 'width:250px', 'id' => 'end'));
			?>
		</div>
		<div class="col-sm-1 col-md-2"></div>
	</div>
	<div class="form-group">
		<div class="col-sm-1 col-md-2"></div>
		<label for="end" class="col-sm-2 control-label">Bezeichnung (optional):</label>
		<div class="col-sm-8 col-md-6">
			<?php
			echo $this->Form->input('bezeichnung', array('label'=>false, 'class' => 'form-control', 'style' => 'width:250px', 'id' => 'bezeichnung'));
			?>
		</div>
		<div class="col-sm-1 col-md-2"></div>
	</div>

	</fieldset>

	<?php echo $this->Form->end(array('label' => 'Speichern', 'class' => 'btn btn-primary col-sm-offset-3 col-md-offset-4')); ?>
</div>