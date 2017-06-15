<div>
<?php echo $this->Form->create('Report', array('class' => 'form-horizontal')); ?>
	<fieldset>
		<legend><?php echo __('Bericht bearbeiten'); ?></legend>
	<?php
		echo $this->Form->input('id');
	?>
	
	<?php
		settype($dataMinutes, 'integer');
		if ($dataMinutes > 0) {
			$hours = floor($dataMinutes / 60);
			$minutes = ($dataMinutes % 60);
		} else {
			$hours = 0;
			$minutes = 0;
		}
	?>
	<div class="form-group">
		<div class="col-sm-1 col-md-2"></div>
		<label for="hours" class="col-sm-2 control-label">Stunden:</label>
		<div class="col-sm-8 col-md-6">
			<?php
				echo $this->Form->input('hours', array('label'=>false, 'type' => 'text', 'id' => 'hours', 'value' => $hours));
			?>
		</div>
		<div class="col-sm-1 col-md-2"></div>
	</div>
	<div class="form-group">
		<div class="col-sm-1 col-md-2"></div>
		<label for="minutes" class="col-sm-2 control-label">Minuten:</label>
		<div class="col-sm-8 col-md-6">
			<?php
			echo $this->Form->input('minutes', array('label'=>false, 'type' => 'text', 'id' => 'minutes', 'value' => $minutes));
			?>
		</div>
		<div class="col-sm-1 col-md-2"></div>
	</div>
	<div class="form-group">
		<div class="col-sm-1 col-md-2"></div>
		<label for="books" class="col-sm-2 control-label">Bücher:</label>
		<div class="col-sm-8 col-md-6">
			<?php
			echo $this->Form->input('books', array('label'=>false, 'class' => 'touch-spin', 'type' => 'text', 'id' => 'books'));
			?>
		</div>
		<div class="col-sm-1 col-md-2"></div>
	</div>
	<div class="form-group">
		<div class="col-sm-1 col-md-2"></div>
		<label for="magazines" class="col-sm-2 control-label">Zeitschriften:</label>
		<div class="col-sm-8 col-md-6">
			<?php
			echo $this->Form->input('magazines', array('label'=>false, 'class' => 'touch-spin', 'type' => 'text', 'id' => 'magazines'));
			?>
		</div>
		<div class="col-sm-1 col-md-2"></div>
	</div>
	<div class="form-group">
		<div class="col-sm-1 col-md-2"></div>
		<label for="brochures" class="col-sm-2 control-label">Broschüren:</label>
		<div class="col-sm-8 col-md-6">
			<?php
			echo $this->Form->input('brochures', array('label'=>false, 'class' => 'touch-spin', 'type' => 'text', 'id' => 'brochures'));
			?>
		</div>
		<div class="col-sm-1 col-md-2"></div>
	</div>
	<div class="form-group">
		<div class="col-sm-1 col-md-2"></div>
		<label for="tracts" class="col-sm-2 control-label">Traktate:</label>
		<div class="col-sm-8 col-md-6">
			<?php
			echo $this->Form->input('tracts', array('label'=>false, 'class' => 'touch-spin', 'type' => 'text', 'id' => 'tracts'));
			?>
		</div>
		<div class="col-sm-1 col-md-2"></div>
	</div>
	<div class="form-group">
		<div class="col-sm-1 col-md-2"></div>
		<label for="videos" class="col-sm-2 control-label">Videovorführungen:</label>
		<div class="col-sm-8 col-md-6">
			<?php
			echo $this->Form->input('videos', array('label'=>false, 'class' => 'touch-spin', 'type' => 'text', 'id' => 'videos'));
			?>
		</div>
		<div class="col-sm-1 col-md-2"></div>
	</div>
	<div class="form-group">
		<div class="col-sm-1 col-md-2"></div>
		<label for="videos" class="col-sm-2 control-label">Visitenkarten:</label>
		<div class="col-sm-8 col-md-6">
			<?php
			echo $this->Form->input('jworgcards', array('label'=>false, 'class' => 'touch-spin', 'type' => 'text', 'id' => 'jworgcards'));
			?>
		</div>
		<div class="col-sm-1 col-md-2"></div>
	</div>
	<div class="form-group">
		<div class="col-sm-1 col-md-2"></div>
		<label for="conversations" class="col-sm-2 control-label">Gespräche:</label>
		<div class="col-sm-8 col-md-6">
			<?php
			echo $this->Form->input('conversations', array('label'=>false, 'class' => 'touch-spin', 'type' => 'text', 'id' => 'conversations'));
			?>
		</div>
		<div class="col-sm-1 col-md-2"></div>
	</div>
	<div class="form-group">
		<div class="col-sm-1 col-md-2"></div>
		<label for="contacts" class="col-sm-2 control-label">Kontaktdaten erhalten:</label>
		<div class="col-sm-8 col-md-6">
			<?php
			echo $this->Form->input('contacts', array('label'=>false, 'class' => 'touch-spin', 'type' => 'text', 'id' => 'contacts'));
			?>
		</div>
		<div class="col-sm-1 col-md-2"></div>
	</div>
	

	</fieldset>

	<?php echo $this->Form->end(array('label' => 'Speichern', 'class' => 'btn btn-primary col-sm-offset-3 col-md-offset-4')); ?>
</div>