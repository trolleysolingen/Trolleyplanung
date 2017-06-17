<?php

//calc the td width
$tdWidth = 100 / ($weekDays['activeDays'] + 1);

echo "<div class='error alert alert-danger' role='alert'></div>";
echo "<table class='table table-striped table-hover'>";

echo "<tr>";
for ($weekDay = 0; $weekDay < sizeof($weekDays['weekDays']); $weekDay++) {
	$echoDate->add(new DateInterval('P1D'));
	if($weekDays['weekDays'][$weekDay] != null) {
		echo "<th style='width:" . $tdWidth . "%;'>";
		echo $weekDays['weekDays'][$weekDay]['display'] . " (" . $echoDate->format("d.m.") . ")";
		echo "</th>";
	}
}
echo "</tr>";

for ($slot = 0; $slot < $weekDays['maxTimeslots']; $slot++) {
	echo "<tr>";
	for ($weekDay = 0; $weekDay < sizeof($weekDays['weekDays']); $weekDay++) {	
		if ($weekDays['weekDays'][$weekDay] != null) {
			if ($slot < sizeof($weekDays['weekDays'][$weekDay]['timeslots'])) {
				echo $this->element('reservation_entry', array(
						'dateStart' => $dateStart,
						'reservations' => $reservations,
						'weekDay' => $weekDay,
						'timeslots' => $weekDays['weekDays'][$weekDay]['timeslots'],
						'publisher' => $publisher,
						'slot' => $slot,
						'td_id' => $display_real_size,
						'div_class' => 'lg',
						'headline' => $weekDays['weekDays'][$weekDay]['timeslots'][$slot]['Timeslot']['start'] . ' - ' . $weekDays['weekDays'][$weekDay]['timeslots'][$slot]['Timeslot']['end'],
						
				));
			} else {
				echo "<td>&nbsp;</td>";
			}
		}
	}
	echo "</tr>";
}

echo "</table>";