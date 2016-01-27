<?php
for ($weekDay = 0; $weekDay < sizeof($weekDays); $weekDay++) {
	$echoDate->add(new DateInterval('P1D'));
	if($weekDays[$weekDay] != "null") {
		echo "<div class='error alert alert-danger' role='alert'></div>";
		echo "<table class='table table-striped table-hover'>";
		echo "<tr>";
		echo "<th colspan='2'>";
		echo $weekDays[$weekDay] . " (" . $echoDate->format("d.m.") . ")";
		echo "</th>";
		echo "</tr>";

		for ($slot = 0; $slot < sizeof($timeslots); $slot++) {
			echo "<tr>";
			echo "<td class='col-xs-3'>";
			echo $timeslots[$slot]['Timeslot']['start'] . " - ";
			echo "<br>";
			echo $timeslots[$slot]['Timeslot']['end'];
			echo "</td>";
			echo $this->element('reservation_entry', array(
				'dateStart' => $dateStart,
				'reservations' => $reservations,
				'weekDay' => $weekDay,
				'timeslots' => $timeslots,
				'publisher' => $publisher,
				'slot' => $slot,
				'td_id' => $display_real_size,
				'div_class' => 'xs'
			));
			echo "</tr>";
		}
		echo "</table>";
	}
}