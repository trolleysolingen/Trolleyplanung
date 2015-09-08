<legend>Aktionen</legend>

<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#manualReportModal" style="margin-bottom: 5px;">
  <span class="glyphicon glyphicon-cogwheels"></span>&nbsp;&nbsp;&nbsp;Bericht manuell eintragen
</button>
<?php if(!empty($missingCongregationReportList)) { ?>
<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#remindReportModal" style="margin-bottom: 5px;">
  <span class="glyphicon glyphicon-message_out"></span>&nbsp;&nbsp;&nbsp;Alle Erinnerungen verschicken
</button>
<?php } ?>
<?php if(!empty($declinedReportList)) { ?>
<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#acceptAllModal" style="margin-bottom: 5px;">
  <span class="glyphicon glyphicon-thumbs_up"></span>&nbsp;&nbsp;&nbsp;Alle verweigerten Berichte akzeptieren
</button>
<?php } ?>
<br/>
<br/>
<?php
	if(empty($missingCongregationReportList) && empty($declinedReportList)) {
		echo "<h1>Keine unbearbeiteten Berichte</h1>";
	} else {
		if(!empty($missingCongregationReportList)) {
?>

<legend>Nicht abgegebene Berichte</legend>

<div class="table-responsive">
	<table width="100%" cellpadding="0" cellspacing="0" class="table table-striped table-hover">
		<thead>
		<tr>
			<th>Datum</th>
			<th>Schicht</th>
			<th>Verkündiger</th>
			<th>Aktionen</th>
		</tr>
		</thead>

		<tbody>

		<?php foreach ($missingCongregationReportList as $missingReport): 
		
			$start_date = new DateTime('2014-01-01 ' . $missingReport['Timeslot']['start'] . ':00');
			$since_start = $start_date->diff(new DateTime('2014-01-01 ' . $missingReport['Timeslot']['end'] . ':00'));
			$calcHours = $since_start->h;
			$calcMinutes =  $since_start->i;
			$partner = array();
			
			foreach($missingReport['Publisher'] as $reservationPublisher) {
				if($reservationPublisher['id'] == 1) {
					$partner[] = $reservationPublisher['PublisherReservation']['guestname'];
				} else {
					$partner[] = $reservationPublisher['prename'] . " " . $reservationPublisher['surname'];
				}
			}
			
			$date = date("d.m.Y", strtotime($missingReport['Reservation']['day'])) . ": " . $missingReport['Timeslot']['start'] . " - " . $missingReport['Timeslot']['end'];
			$adminReason = "";
		?>
			<tr>
				<td><?php echo date("d.m.Y", strtotime($missingReport['Reservation']['day'])); ?>&nbsp;</td>
				<td><?php echo $missingReport['Timeslot']['start'] . " - " . $missingReport['Timeslot']['end']; ?>&nbsp;</td>
				<td>
					<?php 
						foreach ($partner as $reservationPartner) {
							echo $reservationPartner . "<br/>";
						} 
					?>
				</td>
				<td class="actions">
					<?php
						echo $this->Html->link('<button type="button" class="btn btn-default btn-xs" style="margin-right: 5px;"><span class="glyphicon glyphicon-message_out"></span></button>', array('action' => 'remindPublisher', $missingReport['Reservation']['id'], true), array('escape' => false, 'title' => 'Verkündiger erinnern'));
					
						echo '<button onclick="openReportModal(' . $missingReport['Reservation']['id'] . ', ' . $calcHours . ', ' . $calcMinutes . ', \'' . $adminReason . '\');" type="button" data-date="' . $date . '" class="open-ReportDialog btn btn-xs btn-success" style="margin-right: 5px;">'.
							'<span class="glyphicon glyphicon-pencil" ></span>'.
						'</button>';
						
						echo '<button onclick="openReportAdminDismiss(' . $missingReport['Reservation']['id'] . ');" type="button" class="btn btn-danger btn-xs">'.
						'<span class="glyphicon glyphicon-remove" ></span>'.
						'</button>';
					?>
				</td>
			</tr>
		<?php endforeach; ?>

		</tbody>


	</table>
</div>
<?php 
		}
		
		if(!empty($declinedReportList)) {
?>
<br/>

<legend>Verweigerte Berichte</legend>

<div class="table-responsive">
	<table width="100%" cellpadding="0" cellspacing="0" class="table table-striped table-hover">
		<thead>
		<tr>
			<th>Datum</th>
			<th>Schicht</th>
			<th>Verkündiger</th>
			<th>Verweigert am</th>
			<th>Begründung</th>
			<th>Aktionen</th>
		</tr>
		</thead>

		<tbody>

		<?php foreach ($declinedReportList as $declinedReport): 
			$start_date = new DateTime('2014-01-01 ' . $declinedReport['Timeslot']['start'] . ':00');
			$since_start = $start_date->diff(new DateTime('2014-01-01 ' . $declinedReport['Timeslot']['end'] . ':00'));
			$calcHours = $since_start->h;
			$calcMinutes =  $since_start->i;
			
			settype($declinedReport['Reservation']['minutes'], 'integer');
			if ($declinedReport['Reservation']['minutes'] > 0) {
				$hours = floor($declinedReport['Reservation']['minutes'] / 60);
				$minutes = ($declinedReport['Reservation']['minutes'] % 60);
			} else {
				$hours = 0;
				$minutes = 0;
			}
		?>
			<tr>
				<td><?php echo date("d.m.Y", strtotime($declinedReport['Reservation']['day'])); ?>&nbsp;</td>
				<td><?php echo $declinedReport['Timeslot']['start'] . " - " . $declinedReport['Timeslot']['end']; ?>&nbsp;</td>
				<td><?php echo $declinedReport['Reporter']['prename'] . " " . $declinedReport['Reporter']['surname']; ?>&nbsp;</td>
				<td><?php echo date("d.m.Y", strtotime($declinedReport['Reservation']['report_date'])); ?>&nbsp;</td>
				<td><?php echo $declinedReport['Reservation']['no_report_reason']; ?>&nbsp;</td>
				<td class="actions">
					<?php
						echo $this->Html->link('<button type="button" class="btn btn-default btn-xs" style="margin-right: 5px;"><span class="glyphicon glyphicon-pencil"></span></button>', array('action' => 'edit', $declinedReport['Reservation']['id']), array('escape' => false, 'title' => 'Bearbeiten'));
					
						echo $this->Html->link('<button type="button" class="btn btn-success btn-xs" style="margin-right: 5px;"><span class="glyphicon glyphicon-thumbs_up"></span></button>', array('action' => 'acceptDeclinedReport', $declinedReport['Reservation']['id'], true), array('escape' => false, 'title' => 'Akzeptieren'));
						
						echo '<button onclick="openReportReopenModal(' . $declinedReport['Reservation']['id'] . ');" type="button" class="open-ReportDialog btn btn-danger btn-xs">'.
						'<span class="glyphicon glyphicon-unshare" ></span>'.
						'</button>';
					?>
				</td>
			</tr>
		<?php endforeach; ?>

		</tbody>


	</table>
</div>
<?php }
}

echo $this->element('report_modal', array('controller' => 'reports'));?>

<!-- Report Not Necessary Modal -->
<div class="modal fade" id="reportDismissAdminModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
	  <?php echo $this->Form->create(null, array('url' => array('controller' => 'reports', 'action' => 'markReportUnnecessaryAdmin'))); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Bericht nicht nötig</h4>
      </div>
      <div class="modal-body">
        Bist du sicher, dass dieser Bericht <b>nicht abgegeben</b> werden muss? Der Verkündiger und du haben dann keine Möglichkeit mehr das nachzuholen.
		<br/>
		<br/>
		<?php echo $this->Form->input('id', array('id' => 'reservationId1', 'value' => '')); ?>
		
      </div>
      <div class="modal-footer">
		<div class="btn-group">
			<button type="button" class="btn btn-default" data-dismiss="modal">Schließen</button>
			<?php echo $this->Form->end(array('div' => false, 'label' => 'Bestätigen', 'class' => 'btn btn-danger')); ?>
		</div>
      </div>
    </div>
  </div>
</div>

<script>
	function openReportAdminDismiss(id) {
		$('input[id="reservationId1"]').attr("value", id);
		$('#reportDismissAdminModal').modal('show');
	}
</script>

<!-- Report Necessary Modal -->
<div class="modal fade" id="reportReopenModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
	  <?php echo $this->Form->create(null, array('url' => array('controller' => 'reports', 'action' => 'reopenReport'))); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Bericht dem Verkündiger zurückgeben</h4>
      </div>
      <div class="modal-body">
        Bitte gib eine Begründung ein, warum der Verkündiger den Bericht doch abgeben soll.
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
	function openReportReopenModal(id) {
		$("#reservationId2").val(id);
		$('#reportReopenModal').modal('show');
	}
</script>

<!-- Manual Report Modal -->
<div class="modal fade" id="manualReportModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
	  <?php echo $this->Form->create(null, array('url' => array('controller' => 'reports', 'action' => 'saveManualReport'), 'class' => 'form-horizontal')); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Bericht abgeben</h4>
      </div>
      <div class="modal-body">
		<div class="form-group">
			<label class="col-xs-4 control-label" style="text-align: left">Tag:</label>
			<div class='input-group date'>
				<span class="input-group-addon datepickerbutton">
					<span class="glyphicon glyphicon-calendar"></span>
				</span>
				<?php 
					echo $this->Form->input('day', array('div' => false, 'label'=>false, 'class' => 'form-control', 'id' => 'day', 'type' => 'text')); 
				?>
			</div>
		</div>
		<legend></legend>
		<div class="form-group">
			<label class="col-xs-6 control-label" style="text-align: left">Stunden:</label>
			<label class="col-xs-6 control-label" style="text-align: left">Minuten:</label>
		</div>
		<div class="form-group">
			<div class="col-xs-6">
				<?php echo $this->Form->input('hours', array('div' => false, 'label'=>false, 'id' => 'hours', 'type' => 'text', 'value' => '0')); ?>
			</div>
			<div class="col-xs-6">
				<?php echo $this->Form->input('minutes', array('div' => false, 'label'=>false, 'id' => 'minutes', 'type' => 'text', 'value' => '0')); ?>
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
		<legend></legend>
		<div class="form-group">
			<label class="col-xs-4 control-label" style="text-align: left">Gespräche:</label>
			<div class="col-xs-8">
				<?php echo $this->Form->input('conversations', array('div' => false, 'label'=>false, 'class' => 'touch-spin', 'id' => 'conversations', 'type' => 'text')); ?>
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

<div class="modal fade" id="remindReportModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel2">Erinnerungen verschicken</h4>
      </div>
      <div class="modal-body">
        Bist du dir sicher, dass du allen Verkündigern, die zu vergangenen Schichten noch keinen Bericht abgegeben haben, eine Erinnerung schicken willst?
      </div>
      <div class="modal-footer">
        <div class="btn-group">
			<button type="button" class="btn btn-default" data-dismiss="modal">Nein</button>
			<?php
			echo $this->Form->postLink(
					'Ja',
					array('action' => 'sendMultipleReminders'),
					array('class' => 'btn btn-success'),
					false
				);
			?>
		</div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="acceptAllModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel3" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel3">Alle verweigerten Berichte akzeptieren</h4>
      </div>
      <div class="modal-body">
        Bist du dir sicher, dass du alle Berichte, die von Verkündigern verweigert wurden, akzeptieren möchtest? Dies ist nicht mehr rückgängig zu machen.
      </div>
      <div class="modal-footer">
        <div class="btn-group">
			<button type="button" class="btn btn-default" data-dismiss="modal">Nein</button>
			<?php
			echo $this->Form->postLink(
					'Ja',
					array('action' => 'acceptMultipleDeclinedReports'),
					array('class' => 'btn btn-success'),
					false
				);
			?>
		</div>
      </div>
    </div>
  </div>
</div>