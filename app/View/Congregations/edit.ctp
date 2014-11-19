<div class="congregations form">
<?php echo $this->Form->create('Congregation', array('class' => 'form-horizontal')); ?>
	<fieldset>
		<legend><?php echo __('Versammlung bearbeiten'); ?></legend>
		<?php
			echo $this->Form->input('id');
		?>
		<div class="form-group">
			<div class="col-sm-1 col-md-2"></div>
			<label for="start" class="col-sm-2 control-label">Name:</label>
			<div class="col-sm-8 col-md-6">
				<?php
				echo $this->Form->input('name', array('label'=>false, 'class' => 'form-control', 'placeholder' => 'Name', 'id' => 'name'));
				?>
			</div>
			<div class="col-sm-1 col-md-2"></div>
		</div>
		<div class="form-group">
			<div class="col-sm-1 col-md-2"></div>
			<label for="start" class="col-sm-2 control-label">Montag:</label>
			<div class="col-sm-8 col-md-6">
				<?php
				echo $this->Form->input('monday', array('type' => 'checkbox', 'label'=>false, 'class' => 'form-control', 'id' => 'monday'));
				?>
			</div>
			<div class="col-sm-1 col-md-2"></div>
		</div>
		<div class="form-group">
			<div class="col-sm-1 col-md-2"></div>
			<label for="start" class="col-sm-2 control-label">Dienstag:</label>
			<div class="col-sm-8 col-md-6">
				<?php
				echo $this->Form->input('tuesday', array('type' => 'checkbox', 'label'=>false, 'class' => 'form-control', 'id' => 'tuesday'));
				?>
			</div>
			<div class="col-sm-1 col-md-2"></div>
		</div>
		<div class="form-group">
			<div class="col-sm-1 col-md-2"></div>
			<label for="start" class="col-sm-2 control-label">Mittwoch:</label>
			<div class="col-sm-8 col-md-6">
				<?php
				echo $this->Form->input('wednesday', array('type' => 'checkbox', 'label'=>false, 'class' => 'form-control', 'id' => 'wednesday'));
				?>
			</div>
			<div class="col-sm-1 col-md-2"></div>
		</div>
		<div class="form-group">
			<div class="col-sm-1 col-md-2"></div>
			<label for="start" class="col-sm-2 control-label">Donnerstag:</label>
			<div class="col-sm-8 col-md-6">
				<?php
				echo $this->Form->input('thursday', array('type' => 'checkbox', 'label'=>false, 'class' => 'form-control', 'id' => 'thursday'));
				?>
			</div>
			<div class="col-sm-1 col-md-2"></div>
		</div>
		<div class="form-group">
			<div class="col-sm-1 col-md-2"></div>
			<label for="start" class="col-sm-2 control-label">Freitag:</label>
			<div class="col-sm-8 col-md-6">
				<?php
				echo $this->Form->input('friday', array('type' => 'checkbox', 'label'=>false, 'class' => 'form-control', 'id' => 'friday'));
				?>
			</div>
			<div class="col-sm-1 col-md-2"></div>
		</div>
		<div class="form-group">
			<div class="col-sm-1 col-md-2"></div>
			<label for="start" class="col-sm-2 control-label">Samstag:</label>
			<div class="col-sm-8 col-md-6">
				<?php
				echo $this->Form->input('saturday', array('type' => 'checkbox', 'label'=>false, 'class' => 'form-control', 'id' => 'saturday'));
				?>
			</div>
			<div class="col-sm-1 col-md-2"></div>
		</div>
		<div class="form-group">
			<div class="col-sm-1 col-md-2"></div>
			<label for="start" class="col-sm-2 control-label">Sonntag:</label>
			<div class="col-sm-8 col-md-6">
				<?php
				echo $this->Form->input('sunday', array('type' => 'checkbox', 'label'=>false, 'class' => 'form-control', 'id' => 'sunday'));
				?>
			</div>
			<div class="col-sm-1 col-md-2"></div>
		</div>
	</fieldset>

	<?php echo $this->Form->end(array('label' => 'Speichern', 'class' => 'btn btn-primary col-sm-offset-3 col-md-offset-4')); ?>
</div>