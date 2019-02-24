<?php if($publisher['Publisher']['send_mail_when_partner'] == null || $publisher['Publisher']['send_mail_for_reservation'] == null) { ?>
	<div class="alert alert-info" role="alert">In deinem Profil gibt es noch unvollständige Angaben. Bitte klicke <?php echo $this->Html->link('HIER', array('controller' => 'profile', 'action' => 'index'), array('class' => 'alert-link')); ?> um diese zu pflegen. Vielen Dank.</div>
<?php } ?>

<?php echo $this->element('missing_reports');?>

<!-- http://openam.github.io/bootstrap-responsive-tabs/ -->
<ul class="nav nav-tabs responsive" id="myTab" style="margin-top:-20px;">
	<?php
	if (!empty($routes) && sizeof($routes) >= 2) {
	?>
		<li><a href="/reservations/index">Übersicht</a></li>
		<?php
			foreach ($routes as $route) {
				echo '<li><a href="/reservations/index/' . $route['Routes']['id'] . '">' . $route['Routes']['name'] . '</a></li>';
			}
	} else {
	?>
		<li><a href="/reservations/index">Schichtplan</a></li>
	<?php
	}
	?>
	<li class="active"><a href="/reservations/myreservations">Meine Schichten</a></li>
</ul>
<br/><br/>

Hier siehst du alle zukünftigen Schichten für die du dich eingetragen hast:
<br/><br/>

<?php
	if (sizeof($myReservations) > 0) {
		echo "<div class='row'>";
		foreach ($myReservations as $myReservation) {
?>
		<div class="col-xs-12 xol-sm-6 col-md-4 col-lg-3">
			<div class="panel panel-primary">
				<div class="panel-body">
				<strong>Tag: </strong> <?php echo $date = date("d.m.Y", strtotime($myReservation['Reservation']['day'] )) ?><br/>
				<strong>Zeit:</strong> <?php echo $myReservation['Timeslot']['start']?> - <?php echo $myReservation['Timeslot']['end']?> Uhr
				<?php
					if (!empty($myReservation['Route']['name'])) {
				?>
					<br/>
					<strong>Route:</strong> <?php echo $myReservation['Route']['name']?>
				<?php
					}
				?>
				</div>
			</div>
		</div>
<?php
		}
		echo "</div>";
	} else {
		echo "Aktuell hast du keine Schichten reserviert.";
	}
?>

<?php echo $this->element('report_modal', array('controller' => 'reservations'));?>

<?php echo $this->element('report_necessary_modal', array('controller' => 'reservations'));?>
