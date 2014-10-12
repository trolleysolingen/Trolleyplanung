<script>
	var publisherList = [
		<?php
			foreach ($publisherList as $publisherItem) {
				echo "{id:" . $publisherItem['Publisher']['id']. ", name: '" . $publisherItem['Publisher']['prename'] . " " . $publisherItem['Publisher']['surname'] . "'},";
			}
		?>
		];
	var displayTime = '<?php $now = new DateTime('now'); echo $now->format('Y-m-d H:i:s'); ?>';
</script>

<div class="visible-xs-block">
BILDSCHIRM DEBUG:
Größe: XS
</div>

<div class="visible-sm-block">
BILDSCHIRM DEBUG:
Größe: SM
</div>

<div class="visible-md-block">
BILDSCHIRM DEBUG:
Größe: MD
</div>

<div class="visible-lg-block">
BILDSCHIRM DEBUG:
Größe: LG
</div>

<?php
	$weekDays  = array('Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag', 'Sonntag');
?>

<div class="visible-lg-block">
<?php
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
					<?php echo "<a data-toggle='collapse' href='#collapse_lg" . $week . "'>"; ?>
						<span style="font-size: 1.5em; margin-top: -5px;" class="glyphicon glyphicon-expand"></span>
						<?php echo $dateStart->format('d.m.') . " - " . $dateEnd->format("d.m.") . " (KW " . date("W", $dateStart->getTimestamp()). ")"; ?>
					</a>
				</h4>
			</div>
		<?php
			echo "<div id='collapse_lg" . $week . "' class='panel-collapse collapse" . ($week >= Configure::read("DISPLAYED_WEEKS_OPEN") ? "" : " in") . "'>";
			echo "<div class='panel-body'>";

			echo "<table class='table table-striped table-hover'>";
			echo "<div class='error'></div>";
			echo "<tr>";
			echo "<td></td>";
			for ($weekDay = 0; $weekDay < sizeof($weekDays); $weekDay++) {
				echo "<th style='width: 13%;'>";
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
			echo "</div>";
			echo "</div>";
		echo"</div>";

        date_add($dateStart, date_interval_create_from_date_string('7 days'));
        date_add($dateEnd, date_interval_create_from_date_string('7 days'));
    }
?>
</div>


<div class="visible-md-block visible-sm-block">
<?php
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
					<?php echo "<a data-toggle='collapse' href='#collapse_sm_md" . $week . "'>"; ?>
						<span style="font-size: 1.5em; margin-top: -5px;" class="glyphicon glyphicon-expand"></span>
						<?php echo $dateStart->format('d.m.') . " - " . $dateEnd->format("d.m.") . " (KW " . date("W", $dateStart->getTimestamp()). ")"; ?>
					</a>
				</h4>
			</div>
		<?php
			echo "<div id='collapse_sm_md" . $week . "' class='panel-collapse collapse" . ($week >= Configure::read("DISPLAYED_WEEKS_OPEN") ? "" : " in") . "'>";
			echo "<div class='panel-body'>";

			echo "<table class='table table-striped table-hover'>";
			echo "<div class='error'></div>";
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
				echo "<tr>";
				echo "<td>";
				echo "<b>" . substr($weekDays[$weekDay], 0, 2) . "</b>";
				echo "<br>";
				$echoDate->add(new DateInterval('P1D'));
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
			echo "</table>";
			echo "</div>";
			echo "</div>";
		echo"</div>";

        date_add($dateStart, date_interval_create_from_date_string('7 days'));
        date_add($dateEnd, date_interval_create_from_date_string('7 days'));
    }
?>
</div>


<div class="visible-xs-block">
<?php
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
					<?php echo "<a data-toggle='collapse' href='#collapse_xs" . $week . "'>"; ?>
						<span style="font-size: 1.5em; margin-top: -5px;" class="glyphicon glyphicon-expand"></span>
						<?php echo $dateStart->format('d.m.') . " - " . $dateEnd->format("d.m.") . " (KW " . date("W", $dateStart->getTimestamp()). ")"; ?>
					</a>
				</h4>
			</div>
		<?php
			echo "<div id='collapse_xs" . $week . "' class='panel-collapse collapse" . ($week >= Configure::read("DISPLAYED_WEEKS_OPEN") ? "" : " in") . "'>";
			echo "<div class='panel-body'>";

			for ($weekDay = 0; $weekDay < sizeof($weekDays); $weekDay++) {
				echo "<table class='table table-striped table-hover'>";
				echo "<div class='error'></div>";
				echo "<tr>";
				echo "<th colspan='2'>";
				$echoDate->add(new DateInterval('P1D'));
				echo $weekDays[$weekDay] . " (" . $echoDate->format("d.m.") . ")";
				echo "</th>";
				echo "</tr>";
				
				for ($slot = 0; $slot < sizeof($timeslots); $slot++) {
					echo "<tr>";
					echo "<td class='col-xs-3'>";
					echo $timeslots[$slot]['Timeslot']['start'] . " - ";
					echo "<br>";
					echo $timeslots[$slot]['Timeslot']['end'];
					echo "</td>";
					echo $this->element('reservation_entry', array(
						'dateStart' => $dateStart,
						'reservations' => $reservations,
						'weekDay' => $weekDay,
						'timeslots' => $timeslots,
						'publisher' => $publisher,
						'slot' => $slot,
						'td_id' => 'xs',
						'div_class' => 'xs'
					));
					echo "</tr>";
				}
				echo "</table>";
			}
			echo "</div>";
			echo "</div>";
			echo "</div>";

        date_add($dateStart, date_interval_create_from_date_string('7 days'));
        date_add($dateEnd, date_interval_create_from_date_string('7 days'));
    }
?>
</div>


<!-- Guest Modal -->
<div class="modal fade" id="guestModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Partner eintragen</h4>
      </div>
      <div class="modal-body" id="guestModalDiv">
        
      </div>
      <div class="modal-footer" id="guestModalBody">
		<div class="btn-group">
			<button type="button" class="btn btn-default" data-dismiss="modal">Schließen</button>
			<button type="button" class="btn btn-primary">Save changes</button>
		</div>
      </div>
    </div>
  </div>
</div>
