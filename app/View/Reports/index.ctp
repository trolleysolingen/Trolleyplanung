<?php
	if(empty($missingReportList)) {
		echo "<h1>Keine offenen Berichte</h1>";
	} else {
?>

<legend>Meine offenen Berichte</legend>

<div class="table-responsive">
	<table width="100%" cellpadding="0" cellspacing="0" class="table table-striped table-hover">
		<thead>
		<tr>
			<th>Datum</th>
			<th>Schicht</th>
			<th>Partner</th>
			<th>Aktionen</th>
		</tr>
		</thead>

		<tbody>

		<?php foreach ($missingReportList as $missingReport): 
		
			$start_date = new DateTime('2014-01-01 ' . $missingReport['Timeslot']['start'] . ':00');
			$since_start = $start_date->diff(new DateTime('2014-01-01 ' . $missingReport['Timeslot']['end'] . ':00'));
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
				
			if($missingReport['Reservation']['report_necessary'] && $missingReport['Reservation']['no_report_reason'] != null) {
				$rowClass = "danger";
				$adminReason = "<b>Dein Versammlungsadmin sagt:</b><br/><br/>" . $missingReport['Reservation']['no_report_reason'];
			} else {
				$rowClass = "";
				$adminReason = "";
			}
			
			$date = date("d.m.Y", strtotime($missingReport['Reservation']['day'])) . ": " . $missingReport['Timeslot']['start'] . " - " . $missingReport['Timeslot']['end'];
		?>
			<tr class="<?php echo $rowClass; ?>">
				<td><?php echo date("d.m.Y", strtotime($missingReport['Reservation']['day'])); ?>&nbsp;</td>
				<td><?php echo $missingReport['Timeslot']['start'] . " - " . $missingReport['Timeslot']['end']; ?>&nbsp;</td>
				<td><?php 
						foreach ($partner as $reservationPartner) {
							echo $reservationPartner . "<br/>";
						} 
					?>
				</td>
				<td class="actions">
					<?php
						echo '<button onclick="openReportModal(' . $missingReport['Reservation']['id'] . ', ' . $calcHours . ', ' . $calcMinutes . ', \'' . $adminReason . '\');" type="button" data-date="' . $date . '" class="open-ReportDialog btn btn-xs btn-success" style="margin-right: 10px;">'.
							'<span class="glyphicon glyphicon-pencil" ></span>'.
						'</button>';
						
						echo '<button onclick="openReportDismiss(' . $missingReport['Reservation']['id'] . ', \'' . $adminReason . '\');" type="button" data-date="' . $date . '" class="open-ReportDialog btn btn-danger btn-xs">'.
						'<span class="glyphicon glyphicon-remove" ></span>'.
						'</button>';
					?>
				</td>
			</tr>
		<?php endforeach; ?>

		</tbody>


	</table>
</div>

<?php }

echo $this->element('report_modal', array('controller' => 'reports'));?>

<?php echo $this->element('report_necessary_modal', array('controller' => 'reports'));?>