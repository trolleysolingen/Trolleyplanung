<?php
echo "<div class='error alert alert-danger' role='alert'></div>";
echo "<table class='table table-striped table-hover'>";

echo "<tr>";
echo "<td></td>";
$percent = 92/$weekDays['maxTimeslots'];
for ($slot = 0; $slot < $weekDays['maxTimeslots']; $slot++) {   
    echo "<th style='width: " . $percent . "%;'>";    
    echo "</th>";
}
echo "</tr>";

for ($weekDay = 0; $weekDay < sizeof($weekDays['weekDays']); $weekDay++) {
	$echoDate->add(new DateInterval('P1D'));
	if ($weekDays['weekDays'][$weekDay] != null) {
		echo "<tr>";
		echo "<td>";
		echo "<b>" . substr($weekDays['weekDays'][$weekDay]['display'], 0, 2) . "</b>";
		echo "<br>";
		echo " (" . $echoDate->format("d.") . ")";
		echo "</td>";
		for ($slot = 0; $slot < $weekDays['maxTimeslots']; $slot++) {
			if ($slot < sizeof($weekDays['weekDays'][$weekDay]['timeslots'])) {
				echo $this->element('reservation_entry', array(
					'dateStart' => $dateStart,
					'reservations' => $reservations,
					'weekDay' => $weekDay,
					'timeslots' => $weekDays['weekDays'][$weekDay]['timeslots'],
					'publisher' => $publisher,
					'slot' => $slot,
					'td_id' => $display_real_size,
					'div_class' => 'xs',
					'headline' => $weekDays['weekDays'][$weekDay]['timeslots'][$slot]['Timeslot']['start'] . ' - ' . $weekDays['weekDays'][$weekDay]['timeslots'][$slot]['Timeslot']['end'],
				));
			} else {
				echo "<td>&nbsp;</td>";
			}
		}
		echo "</tr>";
	}
}
echo "</table>";