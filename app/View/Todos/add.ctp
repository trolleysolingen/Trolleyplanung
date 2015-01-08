<?php echo $this->Form->create(null, array('url' => array('controller' => 'todos'), 'class' => 'form-horizontal')); ?>
<legend>Neues Todo</legend>
<div class="form-group">
	<div class="col-sm-1 col-md-2"></div>
	<label for="subject" class="col-sm-2 control-label">Typ</label>
	<div class="col-sm-8 col-md-6">
		<div class="radio">
		  <label>
			<input type="radio" name="data[Todo][todotype_id]" id="optionsRadios1" value="1">
			Fehler
		  </label>
		</div>
		<div class="radio">
		  <label>
			<input type="radio" name="data[Todo][todotype_id]" id="optionsRadios2" value="2">
			Funktion
		  </label>
		</div>
	</div>
	<div class="col-sm-1 col-md-2"></div>
</div>

<div class="form-group">
	<div class="col-sm-1 col-md-2"></div>
	<label for="subject" class="col-sm-2 control-label">Kurzbeschreibung</label>
	<div class="col-sm-8 col-md-6">
		<?php echo $this->Form->input('shortdesc', array('div' => false, 'label'=>false, 'class' => 'form-control', 'id' => 'shortdesc')); ?>
	</div>
	<div class="col-sm-1 col-md-2"></div>
</div>

<div class="form-group">
	<div class="col-sm-1 col-md-2"></div>
	<label for="text" class="col-sm-2 control-label">Ausführliche Beschreibung</label>
	<div class="col-sm-8 col-md-6">
		<?php echo $this->Form->textarea('description', array('div' => false, 'label'=>false, 'class' => 'form-control', 'id' => 'description', 'rows' => '10')); ?>
	</div>
	<div class="col-sm-1 col-md-2"></div>
</div>

<div class="form-group">
	<div class="col-sm-offset-3 col-md-offset-4 col-sm-8 col-md-6">
		<?php echo $this->Form->end(array('div' => false, 'label' => 'Abschicken', 'class' => 'btn btn-primary')); 
		echo $this->Html->link('<button type="button" class="btn btn-danger pull-right">Abbrechen</button>', array('action' => 'index'), array('escape' => false)); ?>
	</div>
</div>