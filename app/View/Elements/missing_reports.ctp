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
				try {
					$startTime = $missingReport['Timeslot']['start'] != null ? $missingReport['Timeslot']['start'] : '00:00';
					$endTime = $missingReport['Timeslot']['end'] != null ? $missingReport['Timeslot']['end'] : '00:00';
					
					$start_date = new DateTime('2014-01-01 ' . $startTime . ':00');
					$since_start = $start_date->diff(new DateTime('2014-01-01 ' . $endTime . ':00'));
					$calcHours = $since_start->h;
					$calcMinutes =  $since_start->i;
					$partner = array();
					
					if(count($missingReport['Publisher']) <= 1) {
						$partner[] = "-";
					} else {
						foreach($missingReport['Publisher'] as $reservationPublisher) {
							if($reservationPublisher['id'] != $publisher['Publisher']['id']) {
								if($reservationPublisher['id'] == 1) {
									$partner[] = $reservationPublisher['PublisherReservation']['guestname'];
								} else {
									$partner[] = $reservationPublisher['prename'] . " " . $reservationPublisher['surname'];
								}
							}
						}
					}
					
					$buttonclass = "btn-default";
					
					if($missingReport['Reservation']['report_necessary'] && $missingReport['Reservation']['no_report_reason'] != null) {
						$adminReason = "<b>Dein Versammlungsadmin sagt:</b><br/><br/>" . $missingReport['Reservation']['no_report_reason'];
						$buttonclass = "btn-warning";
					} else {
						$adminReason = "";
					}
					
					$date = date("d.m.Y", strtotime($missingReport['Reservation']['day'])) . ": " . $startTime . " - " . $endTime;
				
					echo '<button onclick="openReportModal(' . $missingReport['Reservation']['id'] . ', ' . $calcHours . ', ' . $calcMinutes . ', \'' . $adminReason . '\');" type="button" data-date="' . $date . '" class="open-ReportDialog btn ' . $buttonclass . ' col-xs-10 cut-div-text" style="margin-bottom: 10px;">'.
						$date;
					foreach($partner as $partnerPublisher) {
						echo "<br/>Partner: " . $partnerPublisher;
					}
					echo '</button>';
					
					echo '<button onclick="openReportDismiss(' . $missingReport['Reservation']['id'] . ', \'' . $adminReason . '\');" type="button" data-date="' . $date . '" class="open-ReportDialog btn btn-danger col-xs-2" style="margin-bottom: 10px;">';
					echo "<span class='glyphicon glyphicon-remove'></span><br/>";
					foreach($partner as $partnerPublisher) {
						echo "<br/>";
					}
					echo '</button>';
				} catch (Exception $e) {
					//debug($missingReport);
				}
			?>			
	</div>	
	<?php } ?>
  </div>
</div>
<br/>
<?php } ?>