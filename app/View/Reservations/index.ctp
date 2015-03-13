<script>
	var publisherList = [
		<?php
			foreach ($publisherList as $publisherItem) {
				echo "'" . $publisherItem['Publisher']['prename'] . " " . $publisherItem['Publisher']['surname'] . "', ";
			}
		?>
		];
</script>
<input type="hidden" id="guestsNotAllowed" name="guestsNotAllowed" value="<?php echo array_key_exists('guests_not_allowed', $publisher['Congregation']) ? $publisher['Congregation']['guests_not_allowed'] : 0 ?>"/>

<?php if($publisher['Publisher']['send_mail_when_partner'] == null) { ?>
	<div class="alert alert-info" role="alert">In deinem Profil gibt es noch unvollständige Angaben. Bitte klicke <?php echo $this->Html->link('HIER', array('controller' => 'profile', 'action' => 'index'), array('class' => 'alert-link')); ?> um diese zu pflegen. Vielen Dank.</div>
<?php } ?>

<?php if($publisher['Congregation']['report'] && !empty($missingReports)) { ?>
<div class="panel panel-danger">
  <div class="panel-heading">
    <h3 class="panel-title">Fehlende Berichte</h3>
  </div>
  <div class="panel-body">
	<?php
		foreach($missingReports as $missingReport) { ?>
	<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 btn-group" style="margin-bottom: 10px;">
			<?php
				$start_date = new DateTime('2014-01-01 ' . $missingReport['Timeslot']['start'] . ':00');
				$since_start = $start_date->diff(new DateTime('2014-01-01 ' . $missingReport['Timeslot']['end'] . ':00'));
				$calcHours = $since_start->h;
				$calcMinutes =  $since_start->i;
			
				if($missingReport['Reservation']['publisher1_id'] == $publisher['Publisher']['id'] && $missingReport['Reservation']['publisher2_id'] != null) {
					if($missingReport['Reservation']['publisher2_id'] == 1) {
						$partner = $missingReport['Reservation']['guestname'];
					} else {
						$partner = $missingReport['Publisher2']['prename'] . " " . $missingReport['Publisher2']['surname'];
					}
				} else if($missingReport['Reservation']['publisher2_id'] == $publisher['Publisher']['id']) {
					$partner = $missingReport['Publisher1']['prename'] . " " . $missingReport['Publisher1']['surname'];
				} else {
					$partner = "-";
				}
				
				$buttonclass = "btn-default";
				
				if($missingReport['Reservation']['report_necessary'] && $missingReport['Reservation']['no_report_reason'] != null) {
					$adminReason = "<b>Dein Versammlungsadmin sagt:</b><br/><br/>" . $missingReport['Reservation']['no_report_reason'];
					$buttonclass = "btn-warning";
				} else {
					$adminReason = "";
				}
				
				$date = date("d.m.Y", strtotime($missingReport['Reservation']['day'])) . ": " . $missingReport['Timeslot']['start'] . " - " . $missingReport['Timeslot']['end'];
			
				echo '<button onclick="openReportModal(' . $missingReport['Reservation']['id'] . ', ' . $calcHours . ', ' . $calcMinutes . ', \'' . $adminReason . '\');" type="button" data-date="' . $date . '" data-partner="' . $partner . '" class="open-ReportDialog btn ' . $buttonclass . ' col-xs-10 cut-div-text" style="margin-bottom: 10px;">'.
					$date . "<br/>".
					"Partner: " . $partner.
				'</button>';
				
				echo '<button onclick="openReportDismiss(' . $missingReport['Reservation']['id'] . ', \'' . $adminReason . '\');" type="button" data-date="' . $date . '" data-partner="' . $partner . '" class="open-ReportDialog btn btn-danger col-xs-2" style="margin-bottom: 10px;">'.
				"<br/><span class='glyphicon glyphicon-remove' style='margin-top: -25px;'></span>".
				'</button>';
			?>
	</div>
	<?php } ?>
  </div>
</div>
<?php } ?>

<legend>Schichten</legend>

<div class="panel panel-primary">
	<div class="panel-heading">
		<h4 class="panel-title">
			<a data-toggle="collapse" href="#collapse_help">
			<span style="font-size: 1.5em; margin-top: -5px;" class="glyphicon glyphicon-expand"></span>
			Hilfe
			</a>
		</h4>
	</div>
	<div id="collapse_help" class="panel-collapse collapse">
		<div class="panel-body">
			Um dich in eine Schicht einzutragen, drücke bitte auf <a href="javascript:void(0)"><span style="margin-left: 10px;" class="glyphicon glyphicon-user_add"></span></a></br>
			Zusätzlich kannst du noch einen Partner zu deiner Schicht eintragen.</br>
			<br/>
			Um an Kontaktinformationen der Verkündiger zu kommen, drücke auf das <a href="javascript:void(0)"><span style="margin-left: 5px;" class="glyphicon glyphicon-iphone"></span></a> neben dem Namen, um dich z.B. mit der Schicht vor und nach dir oder mit deinem Partner absprechen zu können.</br>
			<br/>
			Deine Schicht kannst durch drücken auf <a href="javascript:void(0)"><span style="margin-left: 5px;" class="glyphicon glyphicon-remove"></span></a> wieder löschen. Du hast dann die Option nur dich oder auch deinen Partner in der Schicht mitzulöschen. Bitte tu dies nur, wenn das auch mit deinem Partner abgesprochen ist.<br/>
			<br/>
			Bei Fragen, nutze bitte das <?php echo $this->Html->link('Kontaktformular', array('controller' => 'contact', 'action' => 'index')); ?>
		</div>
	</div>
</div>
			
<?php
	echo $this->element('week_iteration', array(
		'displaySizes' => array('lg')
	));

	echo $this->element('week_iteration', array(
		'displaySizes' => array('sm', 'md')
	));

	echo $this->element('week_iteration', array(
		'displaySizes' => array('xs')
	));
?>

<!-- Guest Modal -->
<div class="modal fade" id="guestModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Partner eintragen</h4>
      </div>
      <div class="modal-body" id="guestModalDiv">
        
      </div>
      <div class="modal-footer" id="guestModalBody">
		<div class="btn-group">
			<button type="button" class="btn btn-default" data-dismiss="modal">Schließen</button>
			<button type="button" class="btn btn-primary">Save changes</button>
		</div>
      </div>
    </div>
  </div>
</div>


<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Schicht löschen</h4>
      </div>
      <div class="modal-body" id="deleteModalDiv">
		 Möchtest du wirklich diese Schicht löschen?
		 <div id="hiddenParams">
		 
		 </div>
         <div class="checkbox" id="partnerCheckbox">
			<label>
				<input id="deletePartner" type="checkbox"> Meinen Parter ebenfalls aus der Schicht löschen 
			</label>
		 </div>
      </div>
      <div class="modal-footer" id="deleteModalBody">
		<div class="btn-group">
			<button type="button" class="btn btn-default" data-dismiss="modal">Schließen</button>
			<a href="javascript:void(0)" class="btn btn-danger" onclick="deletePublisher();">Löschen</a>
		</div>
      </div>
    </div>
  </div>
</div>

<?php echo $this->element('report_modal', array('controller' => 'reservations'));?>

<?php echo $this->element('report_necessary_modal', array('controller' => 'reservations'));?>
