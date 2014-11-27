<?php
echo "<div class='error alert alert-danger' role='alert'></div>";
echo "<table class='table table-striped table-hover'>";
echo "<tr>";
echo "<td></td>";

//calc the td width
$tdWidth = 100 / (sizeof($weekDays) + 1);

for ($weekDay = 0; $weekDay < sizeof($weekDays); $weekDay++) {
    echo "<th style='width:" . $tdWidth . "%;'>";
    $echoDate->add(new DateInterval('P1D'));
    echo $weekDays[$weekDay] . " (" . $echoDate->format("d.m.") . ")";
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
    echo "</tr>";
}
echo "</table>";