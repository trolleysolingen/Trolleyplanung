<script>
	var publisherList = [
		<?php
			foreach ($publisherList as $publisherItem) {
				echo "{id:" . $publisherItem['Publisher']['id']. ", name: '" . $publisherItem['Publisher']['prename'] . " " . $publisherItem['Publisher']['surname'] . "'},";
			}
		?>
		];
</script>

<?php
    $dateCounter = $mondayThisWeek;

    $weekDays  = array('Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag', 'Sonntag');

    $dateStart = new DateTime();
    $dateEnd = new DateTime();
    $dateStart->setTimestamp($mondayThisWeek);
    $dateEnd->setTimestamp($mondayThisWeek);
    date_add($dateEnd, date_interval_create_from_date_string('6 days'));

    for ($week = 0; $week < Configure::read("DISPLAYED_WEEKS"); $week++) { ?>
        <div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">
					<?php echo "<a data-toggle='collapse' href='#collapse" . $week . "'>"; ?>
						<span style="font-size: 1.5em; margin-top: -5px;" class="glyphicon glyphicon-expand"></span>
						<?php echo $dateStart->format('d.m.') . " - " . $dateEnd->format("d.m.") . " (KW " . date("W", $dateStart->getTimestamp()). ")"; ?>
					</a>
				</h4>
			</div>
		<?php
			echo "<div id='collapse" . $week . "' class='panel-collapse collapse" . ($week >= Configure::read("DISPLAYED_WEEKS_OPEN") ? "" : " in") . "'>";
			echo "<div class='panel-body'>";

			echo "<table class='table table-striped table-hover' style='table-layout: fixed;'>";
			echo "<tr>";
			echo "<td></td>";
			for ($weekDay = 0; $weekDay < sizeof($weekDays); $weekDay++) {
				echo "<th style='width: 13%;'>";
				echo $weekDays[$weekDay];
				echo "</th>";
			}
			echo "</tr>";
			for ($slot = 0; $slot < sizeof($timeslots); $slot++) {
				echo "<tr>";
				echo "<td>";
				echo $timeslots[$slot]['Timeslot']['start'] . " - ";
				echo "<br>";
				echo $timeslots[$slot]['Timeslot']['end'];
				echo "</td>";
				for ($weekDay = 0; $weekDay < sizeof($weekDays); $weekDay++) {

					$dateTmp = new DateTime();
					$dateTmp->setTimestamp($dateStart->getTimestamp());
					date_add($dateTmp, date_interval_create_from_date_string($weekDay . ' days'));
					$reservationTmp = null;
					foreach ($reservations as $reservation) {
						if ($reservation['Reservation']['day'] == $dateTmp->format('Y-m-d') &&
							$reservation['Timeslot']['id'] == $timeslots[$slot]['Timeslot']['id']) {
							$reservationTmp = $reservation;
							break;
						} elseif ($reservation['Reservation']['day'] > $dateTmp->format('Y-m-d')) {
							//reservation array is ordered by time -> we can end the search, reservation does not exist
							break;
						}
					}
					
					//define cell statuses for styling and everything else
					if ($reservationTmp == null) {
						$tdstyle = "";
					}
					else {
						if ($reservationTmp['Reservation']['publisher1_id'] != null) {
							$tdstyle = "warning";
						}
						if ($reservationTmp['Reservation']['publisher2_id'] != null) {
							$tdstyle = "danger";
						}
						if (($reservationTmp['Reservation']['publisher2_id'] == $publisher['Publisher']['id']) || ($reservationTmp['Reservation']['publisher1_id'] == $publisher['Publisher']['id'])) {
							$tdstyle = "info";
						}
					}

					echo "<td id='td_" . $dateTmp->format('Y-m-d') . "_" . $timeslots[$slot]['Timeslot']['id'] . "' style='white-space: nowrap; overflow:hidden !important; text-overflow: ellipsis;' class='" . $tdstyle . "'>";
					if ($reservationTmp != null) {
						if ($reservationTmp['Reservation']['publisher1_id'] != null) {
							if ($reservationTmp['Publisher1']['role_id'] == 3) {
								// guest publisher
								echo $reservationTmp['Reservation']['guestname'];
							} else {
								echo $reservationTmp['Publisher1']['prename'] . ' ' . $reservationTmp['Publisher1']['surname'];
							}
							if ($reservationTmp['Reservation']['publisher1_id'] == $publisher['Publisher']['id']) {
								echo " <a href='javascript:void(0)' onclick='deletePublisher(\"" .
									$dateTmp->format('Y-m-d') . "\", " . $timeslots[$slot]['Timeslot']['id'] .
									", " . ($reservationTmp['Reservation']['publisher2_id'] != null ? "true" : "false") . ");'>X</a>";
							}
							echo '<br/>';
						}
						if ($reservationTmp['Reservation']['publisher2_id'] != null) {
							if ($reservationTmp['Publisher2']['role_id'] == 3) {
								// guest publisher
								echo $reservationTmp['Reservation']['guestname'];
							} else {
								echo $reservationTmp['Publisher2']['prename'] . ' ' . $reservationTmp['Publisher2']['surname'];
							}
							if ($reservationTmp['Reservation']['publisher2_id'] == $publisher['Publisher']['id']) {
								echo " <a href='javascript:void(0)' onclick='deletePublisher(\"" .
									$dateTmp->format('Y-m-d') . "\", " . $timeslots[$slot]['Timeslot']['id'] .
									", true);'>X</a>";
							}
							echo '<br/>';
						} else {
							if ($reservationTmp['Reservation']['publisher1_id'] == $publisher['Publisher']['id']) {
								echo "<div id='guestDiv_" . $dateTmp->format('Y-m-d') . "_" . $timeslots[$slot]['Timeslot']['id'] . "'>".
									   "<a href='javascript:void(0)' onclick='displayGuestField(\"" .
									$dateTmp->format('Y-m-d') . "\", " . $timeslots[$slot]['Timeslot']['id'] .
									");'>Partner eintragen</a>" . '</div>';
							} else {
								echo "<a href='javascript:void(0)' onclick='addPublisher(\"" .
									$dateTmp->format('Y-m-d') . "\", " . $timeslots[$slot]['Timeslot']['id'] . ")'><span class='glyphicon glyphicon-user_add'></span></a>" . '<br/>';
							}
						}
					} else {
						echo "<a href='javascript:void(0)' onclick='addPublisher(\"" .
							$dateTmp->format('Y-m-d') . "\", " . $timeslots[$slot]['Timeslot']['id'] . ")'><span class='glyphicon glyphicon-user_add'></span></a>" . '<br/>';
					}

					echo "</td>";
				}
				echo "</tr>";
			}
			echo "</table>";
			echo "</div>";
			echo "</div>";
		echo"</div>";

        date_add($dateStart, date_interval_create_from_date_string('7 days'));
        date_add($dateEnd, date_interval_create_from_date_string('7 days'));
    }
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