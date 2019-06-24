<div class="form-group">
	<label class="col-xs-3 control-label" style="text-align: left">
		<?php echo $this->Form->input('shiplistreport', array('type' => 'hidden', 'value' => true, 'id' => 'shiplistreport'));?>
		Schiffsname</label>
	<label class="col-xs-2 control-label" style="text-align: left">IMO #</label>
	<label class="col-xs-3 control-label" style="text-align: left">Schiffstyp</label>
	<label class="col-xs-2 control-label" style="text-align: left">Empf.</label>
	<label class="col-xs-2 control-label" style="text-align: left">Res.</label>
</div>
<?php 
	for ($i = 0; $i < 10; $i++) {
?>
	<div class="form-group">
		<div class="col-xs-3">
			<?php echo $this->Form->input('shiplist' . $i . '.shipname', array('onblur' => 'toggleRowRequired(' . $i . ')', 'div' => false, 'label'=>false, 'id' => 'shipname' . $i, 'type' => 'text', 'maxlength' => 30,  'class' => 'form-control')); ?>
		</div>
		<div class="col-xs-2">
			<?php echo $this->Form->input('shiplist' . $i . '.imo', array('onblur' => 'toggleRowRequired(' . $i . ')', 'div' => false, 'label'=>false, 'id' => 'imo' . $i, 'type' => 'text', 'maxlength' => 10,  'class' => 'form-control')); ?>
		</div>
		<div class="col-xs-3">
			<?php echo $this->Form->input('shiplist' . $i . '.shiptype', array('onblur' => 'toggleRowRequired(' . $i . ')', 'div' => false, 'label'=>false, 'id' => 'shiptype' . $i,  'class' => 'form-control',
					'empty' => '-fill-',
					'options' => array(
							'Ro-Ro' => 'Ro-Ro',
							'Containerschiff' => 'Containerschiff',
							'Schlepper-Versorger' => 'Schlepper-Versorger',
							'Kreuzfahrtschiff' => 'Kreuzfahrtschiff',
							'Frachter' => 'Frachter',
							'Frachter-Binnenschiff' => 'Frachter-Binnenschiff',
							'Tanker' => 'Tanker',
							'Tanker-Binnenschiff' => 'Tanker-Binnenschiff',
							'Passagierschiff' => 'Passagierschiff',
							'Schubboot' => 'Schubboot',
							'Flusskreuzfahrtschiff' => 'Flusskreuzfahrtschiff',
							'Fähre' => 'Fähre',
							'Schlepper' => 'Schlepper',
							'Unbekannt' => 'Unbekannt'
					)
					
			)); ?>
		</div>
		<div class="col-xs-2">
			<?php echo $this->Form->input('shiplist' . $i . '.recommendation', array('onblur' => 'toggleRowRequired(' . $i . ')', 'div' => false, 'label'=>false, 'id' => 'recommendation' . $i,  'class' => 'form-control',
					'empty' => '-fill-',
					'options' => array(
							'P14D' => '2W',
							'P183D' => '6M',
							'P365D' => '12M'
							)
			)); ?>
		</div>
		<div class="col-xs-2">
			<?php echo $this->Form->input('shiplist' . $i . '.reservation', array('type' => 'checkbox', 'label'=>false, 'id' => 'reservation' . $i, 'style' => 'margin-left:0px;')); ?>
		</div>
	</div>

<?php 
	}
?>

<script>
	function toggleRowRequired(i) {
		if ($("#shipname" + i).val() !== '' || $("#imo" + i).val() !== '' || 
			$("#shiptype" + i).val() !== '' || $("#recommendation" + i).val() !== '') {
			
			$("#shipname" + i).prop('required',true);
			$("#imo" + i).prop('required',true);
			$("#shiptype" + i).prop('required',true);
			$("#recommendation" + i).prop('required',true);
			
		} else {
			
			$("#shipname" + i).prop('required',false);
			$("#imo" + i).prop('required',false);
			$("#shiptype" + i).prop('required',false);
			$("#recommendation" + i).prop('required',false);
			
		}
	}
</script>