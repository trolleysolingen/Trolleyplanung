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
				
			if($missingReport['Reservation']['publisher1_id'] == $publisher['Publisher']['id'] && $missingReport['Reservation']['publisher2_id'] != null) {
				$partner = $missingReport['Publisher2']['prename'] . " " . $missingReport['Publisher2']['surname'];
			} else if($missingReport['Reservation']['publisher2_id'] == $publisher['Publisher']['id']) {
				$partner = $missingReport['Publisher1']['prename'] . " " . $missingReport['Publisher1']['surname'];
			} else {
				$partner = "-";
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
				<td><?php echo $partner; ?>&nbsp;</td>
				<td class="actions">
					<?php
						echo '<button onclick="openReportModal(' . $missingReport['Reservation']['id'] . ', ' . $calcHours . ', ' . $calcMinutes . ', \'' . $adminReason . '\');" type="button" data-date="' . $date . '" data-partner="' . $partner . '" class="open-ReportDialog btn btn-xs btn-success" style="margin-right: 10px;">'.
							'<span class="glyphicon glyphicon-pencil" ></span>'.
						'</button>';
						
						echo '<button onclick="openReportDismiss(' . $missingReport['Reservation']['id'] . ', \'' . $adminReason . '\');" type="button" data-date="' . $date . '" data-partner="' . $partner . '" class="open-ReportDialog btn btn-danger btn-xs">'.
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
	if(!empty($givenReportList)) {
?>
<br/>
<div class="panel panel-success">
	<div class="panel-heading">
		<h4 class="panel-title">
			<a data-toggle='collapse' href='#collapse_givenReports'>
				<span style="font-size: 1.5em; margin-top: -5px;" class="glyphicon glyphicon-expand"></span>
				Abgegebene Berichte
			</a>
		</h4>
	</div>

	<div id='collapse_givenReports' class='panel-collapse collapse'>
		<div class='panel-body'>
			<div class="table-responsive">
				<table width="100%" cellpadding="0" cellspacing="0" class="table table-striped table-hover">
					<thead>
					<tr>
						<th>Datum</th>
						<th>Schicht</th>
						<th>Partner</th>
						<th>Abgegeben</th>
						<th>Std</th>
						<th>Min</th>
						<th>Bü</th>
						<th>Zsc</th>
						<th>Bro</th>
						<th>Tra</th>
						<th>Gsp</th>
						<th>Aktionen</th>
					</tr>
					</thead>

					<tbody>

					<?php foreach ($givenReportList as $givenReport): 
						$start_date = new DateTime('2014-01-01 ' . $givenReport['Timeslot']['start'] . ':00');
						$since_start = $start_date->diff(new DateTime('2014-01-01 ' . $givenReport['Timeslot']['end'] . ':00'));
						$calcHours = $since_start->h;
						$calcMinutes =  $since_start->i;
							
						if($givenReport['Reservation']['publisher1_id'] == $publisher['Publisher']['id'] && $givenReport['Reservation']['publisher2_id'] != null) {
							$partner = $givenReport['Publisher2']['prename'] . " " . $givenReport['Publisher2']['surname'];
						} else if($givenReport['Reservation']['publisher2_id'] == $publisher['Publisher']['id']) {
							$partner = $givenReport['Publisher1']['prename'] . " " . $givenReport['Publisher1']['surname'];
						} else {
							$partner = "-";
						}
						
						settype($givenReport['Reservation']['minutes'], 'integer');
						if ($givenReport['Reservation']['minutes'] > 0) {
							$hours = floor($givenReport['Reservation']['minutes'] / 60);
							$minutes = ($givenReport['Reservation']['minutes'] % 60);
						} else {
							$hours = 0;
							$minutes = 0;
						}
					?>
						<tr>
							<td><?php echo date("d.m.Y", strtotime($givenReport['Reservation']['day'])); ?>&nbsp;</td>
							<td><?php echo $givenReport['Timeslot']['start'] . " - " . $givenReport['Timeslot']['end']; ?>&nbsp;</td>
							<td><?php echo $partner; ?>&nbsp;</td>
							<td><?php echo date("d.m.Y", strtotime($givenReport['Reservation']['report_date'])); ?>&nbsp;</td>
							<td><?php echo $hours; ?>&nbsp;</td>
							<td><?php echo $minutes; ?>&nbsp;</td>
							<td><?php echo $givenReport['Reservation']['books']; ?>&nbsp;</td>
							<td><?php echo $givenReport['Reservation']['magazines']; ?>&nbsp;</td>
							<td><?php echo $givenReport['Reservation']['brochures']; ?>&nbsp;</td>
							<td><?php echo $givenReport['Reservation']['tracts']; ?>&nbsp;</td>
							<td><?php echo $givenReport['Reservation']['conversations']; ?>&nbsp;</td>
							<td class="actions">
								<?php
									
									echo $this->Html->link('<button type="button" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-pencil"></span></button>', array('action' => 'edit', $givenReport['Reservation']['id']), array('escape' => false, 'title' => 'Bearbeiten'));
								?>
							</td>
						</tr>
					<?php endforeach; ?>

					</tbody>


				</table>
			</div>
		</div>
	</div>
</div>

<?php }

echo $this->element('report_modal', array('controller' => 'reports'));?>

<?php echo $this->element('report_necessary_modal', array('controller' => 'reports'));?>