<!-- Report Modal -->
<div class="modal fade" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
	  <?php echo $this->Form->create(null, array('url' => array('controller' => $controller, 'action' => 'saveReport'), 'class' => 'form-horizontal')); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Bericht abgeben</h4>
      </div>
      <div class="modal-body">
		<div class="error alert alert-danger" role="alert" id="reportInfo">
			
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
				
      </div>
      <div class="modal-footer">
		<div class="btn-group">
			<button type="button" class="btn btn-default" data-dismiss="modal">Schließen</button>
			<?php echo $this->Form->end(array('div' => false, 'label' => 'Abschicken', 'class' => 'btn btn-success')); ?>
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