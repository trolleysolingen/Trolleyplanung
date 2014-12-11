<?php
echo "<div class='error alert alert-danger' role='alert'></div>";
echo "<table class='table table-striped table-hover'>";
echo "<tr>";
echo "<td></td>";
for ($slot = 0; $slot < sizeof($timeslots); $slot++) {
    $percent = 92/sizeof($timeslots);
    echo "<th style='width: " . $percent . "%;'>";
    echo $timeslots[$slot]['Timeslot']['start'] . " - " . $timeslots[$slot]['Timeslot']['end'];
    echo "</th>";
}
echo "</tr>";
for ($weekDay = 0; $weekDay < sizeof($weekDays); $weekDay++) {
	$echoDate->add(new DateInterval('P1D'));
	if($weekDays[$weekDay] != "null") {
		echo "<tr>";
		echo "<td>";
		echo "<b>" . substr($weekDays[$weekDay], 0, 2) . "</b>";
		echo "<br>";
		echo " (" . $echoDate->format("d.") . ")";
		echo "</td>";
		for ($slot = 0; $slot < sizeof($timeslots); $slot++) {
			echo $this->element('reservation_entry', array(
				'dateStart' => $dateStart,
				'reservations' => $reservations,
				'weekDay' => $weekDay,
				'timeslots' => $timeslots,
				'publisher' => $publisher,
				'slot' => $slot,
				'td_id' => 'sm_md',
				'div_class' => 'xs'
			));
		}
		echo "</tr>";
	}
}
echo "</table>";