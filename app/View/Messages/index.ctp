<legend>Neue Nachricht</legend>

<?php echo $this->Form->create(null, array('url' => array('controller' => 'messages'), 'class' => 'form-horizontal')); ?>

<div class="form-group">
	<div class="col-sm-1 col-md-2"></div>
	<label for="subject" class="col-sm-2 control-label">An</label>
	<div class="col-sm-8 col-md-6">
		<div class="radio">
		  <label>
			<input type="radio" name="data[Message][group]" id="optionsRadios1" value="myPub">
			Alle Verkündiger meiner Versammlung
		  </label>
		</div>		
		<div class="radio">
		  <label>
			<input type="radio" name="data[Message][group]" id="optionsRadios5" value="myPubDataProtection">
			Alle Verkündiger meiner Versammlung, die der Datenschutzerklärung noch nicht zugestimmt haben
		  </label>
		</div>
		<div class="radio">
		  <label>
			<input type="radio" name="data[Message][group]" id="optionsRadios2" value="myCongAd">
			Alle Versammlungsadmins meiner Versammlung
		  </label>
		</div>
		
		<?php if ($publisher['Role']['name'] == 'admin') {?>
		<br/>
		<legend></legend>
		<div class="radio">
		  <label>
			<input type="radio" name="data[Message][group]" id="optionsRadios3" value="allUsers">
			Alle eingetragenden Verkündiger
		  </label>
		</div>
		<div class="radio">
		  <label>
			<input type="radio" name="data[Message][group]" id="optionsRadios6" value="allUsersDataProtection">
			Alle eingetragenden Verkündiger, die der Datenschutzerklärung noch nicht zugestimmt haben
		  </label>
		</div>
		<div class="radio">
		  <label>
			<input type="radio" name="data[Message][group]" id="optionsRadios4" value="allCongAd">
			Alle eingetragenden Versammlungsadmins
		  </label>
		</div>
		<?php } ?>
	</div>
	<div class="col-sm-1 col-md-2"></div>
</div>

<div class="form-group">
	<div class="col-sm-1 col-md-2"></div>
	<label for="subject" class="col-sm-2 control-label">Betreff</label>
	<div class="col-sm-8 col-md-6">
		<?php echo $this->Form->input('subject', array('div' => false, 'label'=>false, 'class' => 'form-control', 'id' => 'subject')); ?>
	</div>
	<div class="col-sm-1 col-md-2"></div>
</div>

<div class="form-group">
	<div class="col-sm-1 col-md-2"></div>
	<label for="text" class="col-sm-2 control-label">Nachricht</label>
	<div class="col-sm-8 col-md-6">
		<?php echo $this->Form->textarea('text', array('div' => false, 'label'=>false, 'class' => 'form-control', 'id' => 'text', 'rows' => '10')); ?>
	</div>
	<div class="col-sm-1 col-md-2"></div>
</div>

<div class="form-group">
	<div class="col-sm-offset-3 col-md-offset-4">
		<?php echo $this->Form->end(array('div' => false, 'label' => 'Abschicken', 'class' => 'btn btn-primary')); ?>
	</div>
</div>
