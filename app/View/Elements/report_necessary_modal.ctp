<!-- Report Necessary Modal -->
<div class="modal fade" id="reportDismissModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
	  <?php echo $this->Form->create(null, array('url' => array('controller' => $controller, 'action' => 'markReportUnnecessary'))); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Bericht nicht abgeben</h4>
      </div>
      <div class="modal-body">
		<div class="errorHidden alert alert-danger" role="alert" id="reportDismissInfo">
			
		</div>
        Kannst du deinen Bericht für den <b><span id="date" name="date"></span> Uhr</b> aus irgendeinem Grund nicht abgeben, weil du z.B. die Schicht nicht wahrgenommen hast, schreibe eine kurze Begründung und der Versammlungsadmin bekommt eine Info darüber.
		<br/>
		<br/>
		<?php echo $this->Form->input('id', array('id' => 'reservationId2')); ?>
		
		<div class="form-group">
			<label for="reason">Begründung:</label>
			<?php echo $this->Form->textarea('no_report_reason', array('div' => false, 'label'=>false, 'class' => 'form-control', 'id' => 'no_report_reason', 'rows' => '10', 'required')); ?>
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
	function openReportDismiss(id, adminReason) {
		$("#reservationId2").val(id);
		if(adminReason != "") {
			$("#reportDismissInfo").html(adminReason);
			$("#reportDismissInfo").show();
		} else {
			$("#reportDismissInfo").hide();
		}
		$('#reportDismissModal').modal('show');
	}
</script>