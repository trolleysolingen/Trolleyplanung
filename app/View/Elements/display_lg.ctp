<?php
$displayedWeekDays = array();
foreach ($weekDays as $weekDay) {
	if($weekDay != "null"){
		array_push($displayedWeekDays, $weekDay);
	}
}

//calc the td width
$tdWidth = 100 / (sizeof($displayedWeekDays) + 1);

echo "<div class='error alert alert-danger' role='alert'></div>";
echo "<table class='table table-striped table-hover'>";
echo "<tr>";
echo "<td style='width:" . $tdWidth . "%;'></td>";

for ($weekDay = 0; $weekDay < sizeof($weekDays); $weekDay++) {
	$echoDate->add(new DateInterval('P1D'));
	if($weekDays[$weekDay] != "null") {
		echo "<th style='width:" . $tdWidth . "%;'>";
		echo $weekDays[$weekDay] . " (" . $echoDate->format("d.m.") . ")";
		echo "</th>";
	}
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
		if($weekDays[$weekDay] != "null") {
			echo $this->element('reservation_entry', array(
				'dateStart' => $dateStart,
				'reservations' => $reservations,
				'weekDay' => $weekDay,
				'timeslots' => $timeslots,
				'publisher' => $publisher,
				'slot' => $slot,
				'td_id' => 'lg',
				'div_class' => 'lg'
			));
		}
    }
    echo "</tr>";
}
echo "</table>";