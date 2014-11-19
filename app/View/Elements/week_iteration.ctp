<div class="<?php foreach ($displaySizes as $displaySize) { echo "visible-" . $displaySize . "-block ";} ?>">
<?php
    $displaySizes_underscore_separated = implode("_", $displaySizes);

    $weekDays  = array();

    if ($publisher['Congregation']['monday'] == 1) {
        array_push($weekDays, 'Montag');
    }
    if ($publisher['Congregation']['tuesday'] == 1) {
        array_push($weekDays, 'Dienstag');
    }
    if ($publisher['Congregation']['wednesday'] == 1) {
        array_push($weekDays, 'Mittwoch');
    }
    if ($publisher['Congregation']['thursday'] == 1) {
        array_push($weekDays, 'Donnerstag');
    }
    if ($publisher['Congregation']['friday'] == 1) {
        array_push($weekDays, 'Freitag');
    }
    if ($publisher['Congregation']['saturday'] == 1) {
        array_push($weekDays, 'Samstag');
    }
    if ($publisher['Congregation']['sunday'] == 1) {
        array_push($weekDays, 'Sonntag');
    }

    $dateStart = new DateTime();
    $dateEnd = new DateTime();
	$echoDate = new DateTime();
    $dateStart->setTimestamp($mondayThisWeek);
    $dateEnd->setTimestamp($mondayThisWeek);
	$echoDate->setTimestamp($mondayThisWeek);
	$echoDate->sub(new DateInterval('P1D'));
    date_add($dateEnd, date_interval_create_from_date_string('6 days'));

    for ($week = 0; $week < Configure::read("DISPLAYED_WEEKS"); $week++) { ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <?php echo "<a data-toggle='collapse' href='#collapse_". $displaySizes_underscore_separated . $week . "'>"; ?>
                    <span style="font-size: 1.5em; margin-top: -5px;" class="glyphicon glyphicon-expand"></span>
                    <?php echo $dateStart->format('d.m.') . " - " . $dateEnd->format("d.m.") . " (KW " . date("W", $dateStart->getTimestamp()). ")"; ?>
                    </a>
                </h4>
            </div>
            <?php
            echo "<div id='collapse_" . $displaySizes_underscore_separated . $week . "' class='panel-collapse collapse" . ($week >= Configure::read("DISPLAYED_WEEKS_OPEN") ? "" : " in") . "'>";
            echo "<div class='panel-body'>";
			if(sizeof($timeslots)>4 && ($displaySize == 'sm' || $displaySize == 'md')) {
				echo $this->element('display_xs', array(
					'weekDays' => $weekDays,
					'echoDate' => $echoDate,
					'dateStart' => $dateStart
				));
			} else {
				echo $this->element('display_' . $displaySizes_underscore_separated, array(
					'weekDays' => $weekDays,
					'echoDate' => $echoDate,
					'dateStart' => $dateStart
				));
			}
            echo "</div>";
            echo "</div>";
        echo"</div>";

        date_add($dateStart, date_interval_create_from_date_string('7 days'));
        date_add($dateEnd, date_interval_create_from_date_string('7 days'));
    }
    ?>
</div>
