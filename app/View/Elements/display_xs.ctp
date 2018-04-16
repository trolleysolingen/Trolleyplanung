<?php
for ($weekDay = 0; $weekDay < sizeof($weekDays['weekDays']); $weekDay++) {
	$echoDate->add(new DateInterval('P1D'));
	if($weekDays['weekDays'][$weekDay] != null) {		
		echo "<div class='errorHidden alert alert-danger' role='alert'></div>";
		echo "<table class='table table-striped table-hover'>";
		echo "<tr>";
		echo "<th colspan='2'>";
		echo $weekDays['weekDays'][$weekDay]['display'] . " (" . $echoDate->format("d.m.") . ")";
		echo "</th>";
		echo "</tr>";

		for ($slot = 0; $slot < sizeof($weekDays['weekDays'][$weekDay]['timeslots']); $slot++) {
			echo "<tr>";
			echo "<td class='col-xs-3'>";
			echo $weekDays['weekDays'][$weekDay]['timeslots'][$slot]['Timeslot']['start'] . " - ";
			echo "<br>";
			echo $weekDays['weekDays'][$weekDay]['timeslots'][$slot]['Timeslot']['end'];
			echo "</td>";
			echo $this->element('reservation_entry', array(
				'dateStart' => $dateStart,
				'reservations' => $reservations,
				'weekDay' => $weekDay,
				'timeslots' => $weekDays['weekDays'][$weekDay]['timeslots'],
				'publisher' => $publisher,
				'slot' => $slot,
				'td_id' => $display_real_size,
				'div_class' => 'xs',
				'headline' => '',
			));
			echo "</tr>";
		}
		echo "</table>";
	}
}