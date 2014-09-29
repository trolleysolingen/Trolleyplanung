<?php echo $this->Html->link(__('Abmelden'), array('controller' => 'VS-' . $congregation["Congregation"]["path"] . '/reservations', 'action' => 'logout')); ?>

<br/>
Hallo <?php echo $publisher['Publisher']['prename'] . ' '. $publisher['Publisher']['surname']; ?>!

<?php
    $dateCounter = $mondayThisWeek;

    $weekDays  = array('Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag', 'Sonntag');

    $dateStart = new DateTime();
    $dateEnd = new DateTime();
    $dateStart->setTimestamp($mondayThisWeek);
    $dateEnd->setTimestamp($mondayThisWeek);
    date_add($dateEnd, date_interval_create_from_date_string('6 days'));

    for ($week = 0; $week < Configure::read("DISPLAYED_WEEKS"); $week++) {
        echo "<div " . ($week >= Configure::read("DISPLAYED_WEEKS_OPEN") ? "style='display:none'" : "") . ">";
        echo "<table>";
        echo "<tr>";
        echo "<td><b>";

        echo $dateStart->format('d.m.') . " - " . $dateEnd->format("d.m.");
        echo "</b></td>";
        for ($weekDay = 0; $weekDay < sizeof($weekDays); $weekDay++) {
            echo "<td>";
            echo $weekDays[$weekDay];
            echo "</td>";
        }
        echo "</tr>";
        for ($slot = 0; $slot < sizeof($timeslots); $slot++) {
            echo "<tr>";
            echo "<td>";
            echo $timeslots[$slot]['Timeslot']['start'] . " - " . $timeslots[$slot]['Timeslot']['end'];
            echo "</td>";
            for ($weekDay = 0; $weekDay < sizeof($weekDays); $weekDay++) {
                echo "<td>";
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
                        break;
                    }
                }

                if ($reservationTmp != null) {
                    if ($reservationTmp['Reservation']['publisher1_id'] != null) {
                        echo $reservationTmp['Publisher1']['prename'] . ' ' . $reservationTmp['Publisher1']['surname'] .
                            " <a href='javascript:void(0)' onclick='alert(1);'>X</a>" . '<br/>';
                    }
                    if ($reservationTmp['Reservation']['publisher2_id'] != null) {
                        echo $reservationTmp['Publisher2']['prename'] . ' ' . $reservationTmp['Publisher2']['surname'] .
                            " <a href='javascript:void(0)' onclick='alert(1);'>X</a>" . '<br/>';
                    } else {
                        if ($reservationTmp['Reservation']['publisher1_id'] == $publisher['Publisher']['id']) {
                            echo "<a href='javascript:void(0)' onclick='alert(1);'>Partner eintragen</a>" . '<br/>';
                        } else {
                            echo "<a href='javascript:void(0)' onclick='alert(1);'>Eintragen</a>" . '<br/>';
                        }
                    }
                } else {
                    echo "<a href='javascript:void(0)' onclick='addPublisher(\"" . $dateTmp->format('Y-m-d') . "\")'>Eintragen</a>" . '<br/>';
                }

                echo "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
        echo "</div>";

        date_add($dateStart, date_interval_create_from_date_string('7 days'));
        date_add($dateEnd, date_interval_create_from_date_string('7 days'));
    }
?>