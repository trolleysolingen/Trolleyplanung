<!-- Report Modal -->
<div class="modal fade" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
	  <?php echo $this->Form->create(null, array('url' => array('controller' => $controller, 'action' => 'saveReport'), 'class' => 'form-horizontal', 'id' => 'ReservationSaveReportForm')); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Bericht abgeben</h4>
      </div>
      <div class="modal-body">
		<div class="errorHidden alert alert-danger" role="alert" id="reportInfo">
			
		</div>
        Dein Bericht für den <b><span id="date" name="date"></span> Uhr</b>
		<br/>
		<br/>
		<?php echo $this->Form->input('id', array('id' => 'reservationId1')); ?>
		<div class="form-group">
			<label class="col-xs-6 control-label" style="text-align: left">Stunden:</label>
			<label class="col-xs-6 control-label" style="text-align: left">Minuten:</label>
		</div>
		<div class="form-group">
			<div class="col-xs-6">
				<?php echo $this->Form->input('hours', array('div' => false, 'label'=>false, 'id' => 'hours', 'type' => 'text')); ?>
			</div>
			<div class="col-xs-6">
				<?php echo $this->Form->input('minutes', array('div' => false, 'label'=>false, 'id' => 'minutes', 'type' => 'text')); ?>
			</div>
		</div>
		<legend></legend>
		
		<?php 
			if ($publisher['Congregation']['typ'] == 'Hafen') {
		?>
			<div class="form-group">
				<label class="col-xs-6 control-label" style="text-align: left">Abgaben (elektr. & gedruckt):</label>
				<label class="col-xs-6 control-label" style="text-align: left">Videovorführungen:</label>
			</div>
			<div class="form-group">
				<div class="col-xs-6">
					<?php echo $this->Form->input('report_levies', array('label'=>false, 'class' => 'touch-spin', 'type' => 'text', 'id' => 'report_levies')); ?>
				</div>
				<div class="col-xs-6">
					<?php echo $this->Form->input('videos', array('label'=>false, 'class' => 'touch-spin', 'type' => 'text', 'id' => 'videos')); ?>
				</div>
			</div>
		
			<div class="form-group">
				<label class="col-xs-6 control-label" style="text-align: left">Zusammenkunft abgehalten:</label>
				<label class="col-xs-6 control-label" style="text-align: left">Erfahrung (Bitte um Rückruf):</label>
			</div>
			<div class="form-group">
				<div class="col-xs-6">
					<?php echo $this->Form->input('report_meetings', array('label'=>false, 'class' => 'touch-spin', 'type' => 'text', 'id' => 'report_meetings')); ?>
				</div>
				<div class="col-xs-6">
					<?php echo $this->Form->input('report_experiences', array('label'=>false, 'class' => 'touch-spin', 'type' => 'text', 'id' => 'report_experiences')); ?>
				</div>
			</div>
		
		<?php 
			} else {
		?>
		
			<div class="form-group">
				<label class="col-xs-6 control-label" style="text-align: left">Bücher:</label>
				<label class="col-xs-6 control-label" style="text-align: left">Zeitschriften:</label>
			</div>
			<div class="form-group">
				<div class="col-xs-6">
					<?php echo $this->Form->input('books', array('div' => false, 'label'=>false, 'class' => 'touch-spin', 'id' => 'books', 'type' => 'text')); ?>
				</div>
				<div class="col-xs-6">
					<?php echo $this->Form->input('magazines', array('div' => false, 'label'=>false, 'class' => 'touch-spin', 'id' => 'magazines', 'type' => 'text')); ?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-6 control-label" style="text-align: left">Broschüren:</label>
				<label class="col-xs-6 control-label" style="text-align: left">Traktate:</label>
			</div>
			<div class="form-group">
				<div class="col-xs-6">
					<?php echo $this->Form->input('brochures', array('div' => false, 'label'=>false, 'class' => 'touch-spin', 'id' => 'brochures', 'type' => 'text')); ?>
				</div>
				<div class="col-xs-6">
					<?php echo $this->Form->input('tracts', array('div' => false, 'label'=>false, 'class' => 'touch-spin', 'id' => 'tracts', 'type' => 'text')); ?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-6 control-label" style="text-align: left">Videovorführungen:</label>
				<label class="col-xs-6 control-label" style="text-align: left">Visitenkarten</label>
			</div>
			<div class="form-group">
				<div class="col-xs-6">
					<?php echo $this->Form->input('videos', array('div' => false, 'label'=>false, 'class' => 'touch-spin', 'id' => 'videos', 'type' => 'text')); ?>
				</div>
				<div class="col-xs-6">
					<?php echo $this->Form->input('jworgcard', array('div' => false, 'label'=>false, 'class' => 'touch-spin', 'id' => 'jworgcard', 'type' => 'text')); ?>
				</div>
			</div>
		<?php 
			}
		?>
		
		<legend></legend>
		
		<div class="form-group">
			<label class="col-xs-6 control-label" style="text-align: left">Gespräche:</label>
			<label class="col-xs-6 control-label" style="text-align: left">Kontaktdaten erhalten:</label>
		</div>
		<div class="form-group">
			<div class="col-xs-6">
				<?php echo $this->Form->input('conversations', array('div' => false, 'label'=>false, 'class' => 'touch-spin', 'id' => 'conversations', 'type' => 'text')); ?>
			</div>
			<div class="col-xs-6">
				<?php echo $this->Form->input('contacts', array('div' => false, 'label'=>false, 'class' => 'touch-spin', 'id' => 'contacts', 'type' => 'text')); ?>
			</div>
		</div>
		
		<?php 
			if ($publisher['Congregation']['typ'] == 'Hafen') {
		?>
			<div class="form-group">
				<label class="col-xs-6 control-label" style="text-align: left">Angetroffene Sprachen:</label>
				<label class="col-xs-6 control-label" style="text-align: left">Hinweis</label>
			</div>
			<div class="form-group">
				<div class="col-xs-6">
					<?php 
						echo $this->Form->input('report_languages_array', array('label'=>false, 'class' => 'form-control', 'id' => 'report_languages', 'multiple' => true,
							'size' => 10,
							'options' => array(
									'Bulgarisch' => 'Bulgarisch',
									'Burmesisch' => 'Burmesisch',
									'Cebuano' => 'Cebuano',
									'Chinesisch' => 'Chinesisch',
									'Dänisch' => 'Dänisch',
									'Englisch' => 'Englisch',
									'Estnisch' => 'Estnisch',
									'Französisch' => 'Französisch',
									'Georgisch' => 'Georgisch',
									'Gujarati' => 'Gujarati',
									'Hiligaynon' => 'Hiligaynon',
									'Hindi' => 'Hindi',
									'Holländisch' => 'Holländisch',
									'Ilokano' => 'Ilokano',
									'Japanisch' => 'Japanisch',
									'Kapverdisch' => 'Kapverdisch',
									'Konkani' => 'Konkani',
									'Koreanisch' => 'Koreanisch',
									'Litauisch' => 'Litauisch',
									'Malayalam' => 'Malayalam',
									'Marathi' => 'Marathi',
									'Montenegrinisch' => 'Montenegrinisch',
									'Niederländisch' => 'Niederländisch',
									'Polnisch' => 'Polnisch',
									'Portugiesisch' => 'Portugiesisch',
									'Punjabi' => 'Punjabi',
									'Rumänisch' => 'Rumänisch',
									'Russisch' => 'Russisch',
									'Schwedisch' => 'Schwedisch',
									'Serbisch' => 'Serbisch',
									'Seychellenkreol' => 'Seychellenkreol',
									'Singhalesisch' => 'Singhalesisch',
									'Spanisch' => 'Spanisch',
									'Tagalog' => 'Tagalog',
									'Tamil' => 'Tamil',
									'Telugu' => 'Telugu',
									'Tschechisch' => 'Tschechisch',
									'Türkisch' => 'Türkisch',
									'Ukrainisch' => 'Ukrainisch',
									'Urdu' => 'Urdu',
									'Weitere' => 'Weitere',
							)));
					?>
				</div>
				<div class="col-xs-6">
					Was zählt als ...<br/>
					... erreichtes Schiff: Analog zm HzH. Selbst eine Abweisung an der Gangway heißt: Schiff erreicht <br/>
					... Gespräch: Analog zu anderen Dienstzweigen "gilt" jedes Gespräch, das man mit dem Ziel beginnt, Zeugnis zu geben. Selbst wenn man dann doch nicht dazu kam.
				</div>
			</div>
		
		<?php 		
				echo $this->element('report_shiplist');
			}
		?>
      </div>
      <div class="modal-footer">
		<div class="btn-group">
			<button type="button" class="btn btn-default" data-dismiss="modal">Schließen</button>
			<?php 
				echo $this->Form->end(array('div' => false, 'label' => 'Abschicken', 'class' => 'btn btn-success')); 
			?>
		</div>
      </div>
    </div>
  </div>
</div>

<script>
	function openReportModal(id, hours, minutes, adminReason) {
		$("#reservationId1").val(id);
		$("#hours").val(hours);
		$("#minutes").val(minutes);
		if(adminReason != "") {
			$("#reportInfo").html(adminReason);
			$("#reportInfo").show();
		} else {
			$("#reportInfo").hide();
		}
		$('#reportModal').modal('show');
	}
</script>