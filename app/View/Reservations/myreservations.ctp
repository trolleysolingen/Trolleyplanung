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
		foreach ($myReservations as $myReservation) {
?>

			<div class="myreservation-block">
			 <div class="myreservation-row">
				<div class="myreservation-column1"><strong>Tag:</strong></div> 
				<div class="myreservation-column2"><?php echo $date = date("d.m.Y", strtotime($myReservation['Reservation']['day'] )) ?></div>
			 </div>
			 
			 <div class="myreservation-row">
				<div class="myreservation-column1"><strong>Zeit:</strong></div>
				<div class="myreservation-column2"> <?php echo $myReservation['Timeslot']['start']?> - <?php echo $myReservation['Timeslot']['end']?> Uhr</div>
			</div>	
				<?php 
					if (!empty($myReservation['Route']['name'])) {
				?>
					<div class="myreservation-row">
						<div class="myreservation-column1"><strong>Route:</strong> </div>
						<div class="myreservation-column2"><?php echo $myReservation['Route']['name']?></div>
					</div>
				<?php 
					}
				?>
			</div>
<?php 
		}
	} else {
		echo "Aktuell hast du keine Schichten reserviert.";
	}
?>

<?php echo $this->element('report_modal', array('controller' => 'reservations'));?>

<?php echo $this->element('report_necessary_modal', array('controller' => 'reservations'));?>
