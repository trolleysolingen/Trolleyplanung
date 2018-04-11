<div class="publishers form">
<?php
echo $this->Form->create('Route', array('class' => 'form-horizontal')); ?>
	<fieldset>
		<legend><?php echo __('Route bearbeiten'); ?></legend>
		<?php
			echo $this->Form->input('id');
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
		
		<div class="form-group">
			<div class="col-sm-1 col-md-2"></div>
			<label for="surname" class="col-sm-2 control-label">Mögliche Verkündiger:</label>
			<div class="col-sm-8 col-md-6">
				<?php
					echo $this->Form->input('publishers', array('div' => false, 'label'=>false, 'class' => 'touch-spin', 'id' => 'publishers', 'type' => 'text'));
				?>
			</div>
			<div class="col-sm-1 col-md-2"></div>
		</div>
		
		<div class="form-group">
			<div class="col-sm-1 col-md-2"></div>
			<label for="surname" class="col-sm-2 control-label">Anzuzeigende Wochen:</label>
			<div class="col-sm-8 col-md-6">
				<?php
					echo $this->Form->input('weeks_displayed', array('div' => false, 'label'=>false, 'class' => 'touch-spin', 'id' => 'weeks_displayed', 'type' => 'text'));
				?>
			</div>
			<div class="col-sm-1 col-md-2"></div>
		</div>
		
		<div class="form-group">
			<div class="col-sm-1 col-md-2"></div>
			<label for="start" class="col-sm-2 control-label">Aktiv:</label>
			<div class="col-sm-8 col-md-6">
				<?php
				echo $this->Form->input('aktiv', array('type' => 'checkbox', 'label'=>false, 'id' => 'aktiv', 'style' => 'margin-left:0px;'));
				?>
			</div>
		</div>
		
		<div class="form-group">
			<div class="col-sm-1 col-md-2"></div>
			<label for="surname" class="col-sm-2 control-label">Link auf eine Karte:</label>
			<div class="col-sm-8 col-md-6">
				<?php
					echo $this->Form->input('maplink', array('label'=>false, 'class' => 'form-control', 'placeholder' => 'Link auf Karte', 'id' => 'maplink'));
				?>
			</div>
			<div class="col-sm-1 col-md-2"></div>
		</div>
	</fieldset>

	<?php echo $this->Form->end(array('label' => 'Speichern', 'class' => 'btn btn-primary col-sm-offset-3 col-md-offset-4')); ?>
</div>