<div class="congregations form">
	<?php echo $this->Form->create('Congregation', array('class' => 'form-horizontal')); ?>
	<fieldset>
		<legend><?php echo __('Versammlung anlegen'); ?></legend>
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
			<label for="start" class="col-sm-2 control-label">Typ:</label>
			<div class="col-sm-8 col-md-6">
				<?php
				echo $this->Form->input('typ', array('label'=>false, 'class' => 'form-control', 'id' => 'typ', 
						'options' => array(
								'Trolley' => 'Trolley', 
								'FFD' => 'FFD', 
								'Hafen' => 'Hafen',
								'SWH' => 'SWH'
						)));
				?>
			</div>			
			<div class="col-sm-1 col-md-2"></div>
		</div>		

	</fieldset>

	<?php echo $this->Form->end(array('label' => 'Speichern', 'class' => 'btn btn-primary col-sm-offset-3 col-md-offset-4')); ?>
</div>