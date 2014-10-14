<?php
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

echo "<td id='td_" . $td_id . "_" . $dateTmp->format('Y-m-d') . "_" . $timeslots[$slot]['Timeslot']['id'] . "' class='" . $tdstyle . "'><div class='row'>";
echo "<div style='padding-right: 5px;' class='col-sm-10 col-xs-8 cut-div-text pull-left'>";
if ($reservationTmp == null) {
    echo "<a href='javascript:void(0)' onclick='addPublisher(\"" .
        $dateTmp->format('Y-m-d') . "\", " . $timeslots[$slot]['Timeslot']['id'] . ", \"" . $displayTime. "\")'><span class='glyphicon glyphicon-user_add'></span></a>";
} else {
    if ($reservationTmp['Reservation']['publisher1_id'] != null) {
        if ($reservationTmp['Publisher1']['role_id'] == 3) {
            // guest publisher
            echo $reservationTmp['Reservation']['guestname'];
        } else {
            echo $reservationTmp['Publisher1']['prename'] . ' ' . $reservationTmp['Publisher1']['surname'];
        }
    }
    echo "</div>";
	echo "<div class='hidden-xs'>";
    echo "<div class='col-sm-2' style='padding-right: 10px;'>";
    if ($reservationTmp['Reservation']['publisher1_id'] != null) {
        if ($reservationTmp['Reservation']['publisher1_id'] == $publisher['Publisher']['id']) {
            echo " <a href='javascript:void(0)' style='float:right;' onclick='showDeleteModal(\"" .
                $dateTmp->format('Y-m-d') . "\", " . $timeslots[$slot]['Timeslot']['id'] .
                ", " . ($reservationTmp['Reservation']['publisher2_id'] != null ? "true" : "false") . ");'><span class='glyphicon glyphicon-remove'></span></a>"; 
        }
        else {
            if($reservationTmp['Publisher1']['phone'] != null) {
				$tel1 = $reservationTmp['Publisher1']['phone'];
                echo "<a href='javascript:void(0)' style='float:right;' tabindex='0' data-toggle='popover' data-trigger='focus' data-placement='top'" .
                     "data-content='<a href=\"tel:" . $tel1 . "\">" . $tel1 . "</a>'><span class='glyphicon glyphicon-iphone'></span></a>";
            }
        }
    }
    echo "</div>";
	echo "</div>";
    echo "</div>";
    echo "<div class='row'>";
    echo "<div style='padding-right: 5px;' class='col-sm-10 col-xs-8 cut-div-text pull-left'>";
    if ($reservationTmp['Reservation']['publisher2_id'] != null) {
        if ($reservationTmp['Publisher2']['role_id'] == 3) {
            // guest publisher
            echo $reservationTmp['Reservation']['guestname'];
        } else {
            echo $reservationTmp['Publisher2']['prename'] . ' ' . $reservationTmp['Publisher2']['surname'];
        }
    } else {
        if ($reservationTmp['Reservation']['publisher1_id'] == $publisher['Publisher']['id']) {
            echo "<div id='guestDiv_" . $dateTmp->format('Y-m-d') . "_" . $timeslots[$slot]['Timeslot']['id'] . "'>".
                "<a href='javascript:void(0)' title='Partner eintragen' onclick='displayGuestField(\"" .
                $dateTmp->format('Y-m-d') . "\", " . $timeslots[$slot]['Timeslot']['id'] . ", \"" . $displayTime .
                "\");'><span class='glyphicon glyphicon-plus' style='margin-top:-5px;' ></span> Partner</a>" . '</div>';
        } else {
            echo "<a href='javascript:void(0)' onclick='addPublisher(\"" .
                $dateTmp->format('Y-m-d') . "\", " . $timeslots[$slot]['Timeslot']['id'] . ", \"" . $displayTime. "\")'><span class='glyphicon glyphicon-user_add'></span></a>" . '<br/>';
        }
    }
    echo "</div>";
    echo "<div class='col-sm-2 col-xs-4' style='padding-right: 10px;'>";
	echo "<div class='hidden-xs'>";
    if ($reservationTmp['Reservation']['publisher2_id'] != null) {
        if ($reservationTmp['Reservation']['publisher2_id'] == $publisher['Publisher']['id']) {
            echo " <a href='javascript:void(0)' style='float:right;' onclick='showDeleteModal(\"" .
                $dateTmp->format('Y-m-d') . "\", " . $timeslots[$slot]['Timeslot']['id'] .
                ", true);'><span class='glyphicon glyphicon-remove'></span></a>";
        }
        else {
            if($reservationTmp['Publisher2']['phone'] != null) {
				$tel2 = $reservationTmp['Publisher2']['phone'];
                echo " <a href='javascript:void(0)' style='float:right;' tabindex='0' data-toggle='popover' data-trigger='focus' data-placement='top'" .
                    "data-content='<a href=\"tel:" . $tel2 . "\">" . $tel2 . "</a>'><span class='glyphicon glyphicon-iphone'></span></a>";
            }
        }
    }
    echo "</div>";
	echo "<div class='visible-xs-block' style='margin-top:-15px;'>";
	echo "<div class='btn-group'>";
	if ($reservationTmp['Reservation']['publisher1_id'] != null) {
        if ($reservationTmp['Reservation']['publisher1_id'] == $publisher['Publisher']['id']) {
            echo " <a href='javascript:void(0)' class='btn btn-danger btn-sm' onclick='showDeleteModal(\"" .
                $dateTmp->format('Y-m-d') . "\", " . $timeslots[$slot]['Timeslot']['id'] .
                ", " . ($reservationTmp['Reservation']['publisher2_id'] != null ? "true" : "false") . ");'><span class='glyphicon glyphicon-remove'></span></a>"; 
        }
        else {
            if($reservationTmp['Publisher1']['phone'] != null) {
				$tel1 = $reservationTmp['Publisher1']['phone'];
                echo "<a href='javascript:void(0)' class='btn btn-primary btn-sm' tabindex='0' data-toggle='popover' title='".
				$reservationTmp['Publisher1']['prename'] . ' ' . $reservationTmp['Publisher1']['surname'] . "' data-trigger='focus' data-placement='top'" .
                     "data-content='<a href=\"tel:" . $tel1 . "\">" . $tel1 . "</a>'><span class='glyphicon glyphicon-iphone'></span></a>";
            }
        }
    }
	
	if ($reservationTmp['Reservation']['publisher2_id'] != null) {
        if ($reservationTmp['Reservation']['publisher2_id'] == $publisher['Publisher']['id']) {
            echo " <a href='javascript:void(0)' class='btn btn-primary btn-sm' onclick='showDeleteModal(\"" .
                $dateTmp->format('Y-m-d') . "\", " . $timeslots[$slot]['Timeslot']['id'] .
                ", true);'><span class='glyphicon glyphicon-remove'></span></a>";
        }
        else {
            if($reservationTmp['Publisher2']['phone'] != null) {
				$tel2 = $reservationTmp['Publisher2']['phone'];
                echo " <a href='javascript:void(0)' class='btn btn-primary btn-sm' tabindex='0' data-toggle='popover' title='".
				$reservationTmp['Publisher2']['prename'] . ' ' . $reservationTmp['Publisher2']['surname'] . "' data-trigger='focus' data-placement='top'" .
                    "data-content='<a href=\"tel:" . $tel2 . "\">" . $tel2 . "</a>'><span class='glyphicon glyphicon-iphone'></span></a>";
            }
        }
    }
	echo "</div>";
	echo "</div>";
    echo "</div>";
}
echo "</div></td>";