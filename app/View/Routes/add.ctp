<div class="publishers form">
<?php
echo $this->Form->create('Route', array('class' => 'form-horizontal')); ?>
	<fieldset>
		<legend><?php echo __('Route bearbeiten'); ?></legend>
		<?php
			echo $this->Form->input('congregation_id', array(
				'type' => 'hidden',
				'value' => $publisher['Congregation']['id']
			));
		?>

		<div class="form-group">
			<div class="col-sm-1 col-md-2"></div>
			<label for="prename" class="col-sm-2 control-label">Name:</label>
			<div class="col-sm-8 col-md-6">
				<?php
				echo $this->Form->input('name', array('label'=>false, 'class' => 'form-control', 'placeholder' => 'Name', 'id' => 'name'));
				?>
			</div>
			<div class="col-sm-1 col-md-2"></div>
		</div>
		<div class="form-group">
			<div class="col-sm-1 col-md-2"></div>
			<label for="surname" class="col-sm-2 control-label">Beschreibung:</label>
			<div class="col-sm-8 col-md-6">
				<?php
					echo $this->Form->input('description', array('label'=>false, 'class' => 'form-control', 'placeholder' => 'Beschreibung', 'id' => 'description'));
				?>
			</div>
			<div class="col-sm-1 col-md-2"></div>
		</div>
	</fieldset>

	<?php echo $this->Form->end(array('label' => 'Speichern', 'class' => 'btn btn-primary col-sm-offset-3 col-md-offset-4')); ?>
</div>
