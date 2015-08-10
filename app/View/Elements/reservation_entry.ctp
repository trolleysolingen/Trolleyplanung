<?php
$tdstyle = "";
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

$me = false;
if (is_array($reservationTmp['Publisher']) || is_object($reservationTmp['Publisher'])) {
	foreach($reservationTmp['Publisher'] as $reservationPublisher) {
		if($reservationPublisher['id'] == $publisher['Publisher']['id']) {
			$me = true;
		}
	}
}

//define cell statuses for styling and everything else
if ($reservationTmp != null) {
    if (count($reservationTmp['Publisher']) > 0 && count($reservationTmp['Publisher']) < $reservationTmp['Route']['publishers']) {
        $tdstyle = "warning";
    }
    if (count($reservationTmp['Publisher']) == $reservationTmp['Route']['publishers']) {
        $tdstyle = "danger";
    }
    if ($me) {
        $tdstyle = "info";
    }
}

echo "<td id='td_" . $td_id . "_" . $dateTmp->format('Y-m-d') . "_" . $timeslots[$slot]['Timeslot']['id'] . "' class='" . $tdstyle . "'>";

if ($reservationTmp == null) {
	echo "<div class='row'>";
	echo "<div style='padding-right: 5px;' class='col-sm-10 col-xs-8 cut-div-text pull-left'>";
    echo "<a href='javascript:void(0)' onclick='addPublisher(\"" .
        $dateTmp->format('Y-m-d') . "\", " . $timeslots[$slot]['Timeslot']['id'] . ", \"" . $displayTime. "\")'><span class='glyphicon glyphicon-user_add'></span></a>";
	echo "</div>";
	echo "</div>";
} else {
	$i = 0;
	foreach($reservationTmp['Publisher'] as $reservationPublisher) {
		echo "<div class='row'>";
		echo "<div style='padding-right: 5px;' class='col-sm-10 col-xs-8 cut-div-text pull-left'>";
		
		if($publisher['Congregation']['key_management'] && $reservationPublisher['kdhall_key']) {
			echo "<span class='glyphicon glyphicon-keys' style='margin-right:5px; margin-top:-5px; color:#f0ad4e'></span>";
		}
		
		if ($reservationPublisher['role_id'] == 3) {
            // guest publisher
            echo $reservationTmp['PublisherReservation'][$i]['guestname'];
        } else {
            echo $reservationPublisher['prename'] . ' ' . $reservationPublisher['surname'];
        }
		
		if(count($reservationPublisher) == $i+1 && $reservationTmp['Route']['publishers'] > count($reservationPublisher)) {
			if($me) {
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
		echo "<div class='hidden-xs'>";
		echo "<div class='col-sm-2' style='padding-right: 10px;'>";
		
		if($reservationPublisher['id'] == $publisher['Publisher']['id']) {
			 echo " <a href='javascript:void(0)' style='float:right;' onclick='showDeleteModal(\"" .
                $dateTmp->format('Y-m-d') . "\", " . $timeslots[$slot]['Timeslot']['id'] .
                ", " . (count($reservationPublisher) > 1 ? "true" : "false") . ");'><span class='glyphicon glyphicon-remove'></span></a>";
		} else {
			if($reservationPublisher['phone'] != null) {
				$tel = $reservationPublisher['phone'];
                echo " <a href='javascript:void(0)' style='float:right;' tabindex='0' data-toggle='popover' data-trigger='focus' data-placement='top'" .
                    "data-content='<a href=\"tel:" . $tel . "\">" . $tel . "</a>'><span class='glyphicon glyphicon-iphone'></span></a>";
            }
		}
		
		echo "</div>";
		echo "<div class='visible-xs-block' style='margin-top:-15px; margin-left: -10px;'>";
		echo "<div class='btn-group'>";
		
		if($me) {
			echo " <a href='javascript:void(0)' class='btn btn-danger btn-sm' onclick='showDeleteModal(\"" .
                $dateTmp->format('Y-m-d') . "\", " . $timeslots[$slot]['Timeslot']['id'] .
                ", " . (count($reservationPublisher) > 1 ? "true" : "false") . ");'><span class='glyphicon glyphicon-remove'></span></a>"; 
		}
		
		echo "</div>";
		echo "</div>";
		echo "</div>";
		echo "</div>";
		echo "</div>";
		
		$i++;
	}
}

echo "</td>";