<script>
	var publisherList = [
		<?php
			foreach ($publisherList as $publisherItem) {
				echo $this->Js->value($publisherItem['Publisher']['prename'] . " " . $publisherItem['Publisher']['surname']) . ", ";
			}
		?>
		];
</script>
<input type="hidden" id="guestsNotAllowed" name="guestsNotAllowed" value="<?php echo array_key_exists('guests_not_allowed', $publisher['Congregation']) ? $publisher['Congregation']['guests_not_allowed'] : 0 ?>"/>

<?php if($publisher['Publisher']['send_mail_when_partner'] == null || $publisher['Publisher']['send_mail_for_reservation'] == null) { ?>
	<div class="alert alert-info" role="alert">In deinem Profil gibt es noch unvollständige Angaben. Bitte klicke <?php echo $this->Html->link('HIER', array('controller' => 'profile', 'action' => 'index'), array('class' => 'alert-link')); ?> um diese zu pflegen. Vielen Dank.</div>
<?php } ?>

<?php if($publisher['Congregation']['report'] && !empty($missingReports)) { ?>
<div class="panel panel-danger">
  <div class="panel-heading">
    <h3 class="panel-title">Fehlende Berichte</h3>
  </div>
  <div class="panel-body">
	<?php
		foreach($missingReports as $missingReport) { ?>
	<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 btn-group" style="margin-bottom: 10px;">
			<?php
				$start_date = new DateTime('2014-01-01 ' . $missingReport['Timeslot']['start'] . ':00');
				$since_start = $start_date->diff(new DateTime('2014-01-01 ' . $missingReport['Timeslot']['end'] . ':00'));
				$calcHours = $since_start->h;
				$calcMinutes =  $since_start->i;
				$partner = array();
				
				if(count($missingReport['Publisher']) <= 1) {
					$partner[] = "-";
				} else {
					foreach($missingReport['Publisher'] as $reservationPublisher) {
						if($reservationPublisher['id'] != $publisher['Publisher']['id']) {
							if($reservationPublisher['id'] == 1) {
								$partner[] = $reservationPublisher['PublisherReservation']['guestname'];
							} else {
								$partner[] = $reservationPublisher['prename'] . " " . $reservationPublisher['surname'];
							}
						}
					}
				}
				
				$buttonclass = "btn-default";
				
				if($missingReport['Reservation']['report_necessary'] && $missingReport['Reservation']['no_report_reason'] != null) {
					$adminReason = "<b>Dein Versammlungsadmin sagt:</b><br/><br/>" . $missingReport['Reservation']['no_report_reason'];
					$buttonclass = "btn-warning";
				} else {
					$adminReason = "";
				}
				
				$date = date("d.m.Y", strtotime($missingReport['Reservation']['day'])) . ": " . $missingReport['Timeslot']['start'] . " - " . $missingReport['Timeslot']['end'];
			
				echo '<button onclick="openReportModal(' . $missingReport['Reservation']['id'] . ', ' . $calcHours . ', ' . $calcMinutes . ', \'' . $adminReason . '\');" type="button" data-date="' . $date . '" class="open-ReportDialog btn ' . $buttonclass . ' col-xs-10 cut-div-text" style="margin-bottom: 10px;">'.
					$date;
				foreach($partner as $partnerPublisher) {
					echo "<br/>Partner: " . $partnerPublisher;
				}
				echo '</button>';
				
				echo '<button onclick="openReportDismiss(' . $missingReport['Reservation']['id'] . ', \'' . $adminReason . '\');" type="button" data-date="' . $date . '" class="open-ReportDialog btn btn-danger col-xs-2" style="margin-bottom: 10px;">';
				echo "<span class='glyphicon glyphicon-remove'></span><br/>";
				foreach($partner as $partnerPublisher) {
					echo "<br/>";
				}
				echo '</button>';
			?>
	</div>
	<?php } ?>
  </div>
</div>
<?php } ?>

<?php
	if (!empty($routes) && sizeof($routes) >= 2) {
?>
		<!-- http://openam.github.io/bootstrap-responsive-tabs/ -->
		<ul class="nav nav-tabs responsive" id="myTab" style="margin-top:-20px;">
			<li <?php echo empty($routeId) ? 'class="active"' : '' ?> ><a href="/reservations/index">Übersicht</a></li>
			<?php
				foreach ($routes as $route) {
					echo '<li ' . (!empty($routeId) && $routeId == $route['Routes']['id'] ? 'class="active"' : '') . '><a href="/reservations/index/' . $route['Routes']['id'] . '">' . $route['Routes']['name'] . '</a></li>';
				}
			?>
		</ul>
		<br/><br/>
<?php
	}
?>

<?php
	if (empty($routeId)) {
	$i=1;
?>
		<div class="tab-content responsive">
			<?php
				foreach ($routes as $route) {
			?>		
				  <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
					<div class="iconbox">
					  <div class="iconbox-icon">
						<span style="padding-top:25px; font-family: 'Stalemate', serif;"><b><?php echo $i; ?></b></span>
					  </div>
					  <div class="featureinfo">
						<h4 class="text-center"><?php echo $route['Routes']['name'] ?></h4>
						<p>
						  <?php echo nl2br($route['Routes']['description']); ?>
						</p>
						<?php if (glob("img/routes/route_" . $route['Routes']['id'] . ".*")) { 
							$file = glob('img/routes/route_' . $route['Routes']['id'] . '.*');
							echo '<button data-toggle="modal" data-target="#routeModal" type="button" data-data="' . '/' . $file[0] . '" class="open-RouteDialog btn btn-warning">';
							echo 'Karte';
							echo '</button>';
							} 
						?>
						<?php if ($route['Routes']['maplink'] != "") {
						?>
							<a class="btn btn-warning" target="_blank" href="<?php echo $route['Routes']['maplink'] ?>">Karte</a>
						<?php
							}
						?>
						<a class="btn btn-success" href="/reservations/index/<?php echo $route['Routes']['id'] ?>">Planung zeigen</a>
					  </div>
					</div>
				  </div>
			<?php
					if($i % 3 == 0) {
						echo "<div class='clearfix visible-lg-block'></div>";
					} else if($i % 2 == 0) {
						echo "<div class='clearfix visible-md-block visible-sm-block'></div>";
					}
					$i++;
				}
			?>
		</div>
<?php
	} else {
?>
		<legend>Schichten</legend>

		<div class="panel panel-primary">
			<div class="panel-heading">
				<h4 class="panel-title">
					<a data-toggle="collapse" href="#collapse_help">
						<span style="font-size: 1.5em; margin-top: -5px;" class="glyphicon glyphicon-expand"></span>
						Hilfe
					</a>
				</h4>
			</div>
			<div id="collapse_help" class="panel-collapse collapse">
				<div class="panel-body">
					Um dich in eine Schicht einzutragen, drücke bitte auf <a href="javascript:void(0)"><span
							style="margin-left: 10px;" class="glyphicon glyphicon-user_add"></span></a></br>
					Zusätzlich kannst du noch einen Partner zu deiner Schicht eintragen.</br>
					<br/>
					Um an Kontaktinformationen der Verkündiger zu kommen, drücke auf das <a
						href="javascript:void(0)"><span style="margin-left: 5px;"
														class="glyphicon glyphicon-iphone"></span></a> neben dem Namen,
					um dich z.B. mit der Schicht vor und nach dir oder mit deinem Partner absprechen zu können.</br>
					<br/>
					Deine Schicht kannst durch drücken auf <a href="javascript:void(0)"><span style="margin-left: 5px;"
																							  class="glyphicon glyphicon-remove"></span></a>
					wieder löschen. Du hast dann die Option nur dich oder auch deinen Partner in der Schicht
					mitzulöschen. Bitte tu dies nur, wenn das auch mit deinem Partner abgesprochen ist.<br/>
					<br/>
					Bei Fragen, nutze bitte
					das <?php echo $this->Html->link('Kontaktformular', array('controller' => 'contact', 'action' => 'index')); ?>
				</div>
			</div>
		</div>
		
		<?php if($publisher['Publisher']['role_id'] == 4 || $publisher['Publisher']['role_id'] == 2) { ?>
		
		<div class="panel panel-success">
			<div class="panel-heading">
				<h4 class="panel-title">
					<a data-toggle="collapse" href="#collapse_admin">
						<span style="font-size: 1.5em; margin-top: -5px;" class="glyphicon glyphicon-expand"></span>
						Admin Tools
					</a>
				</h4>
			</div>
			<div id="collapse_admin" class="panel-collapse collapse">
				<div class="panel-body">
					Die Admin Tools bieten dir verschiedenste Möglichkeiten den Schichtplan zu manipulieren.<br/>
					Sie werden durch ein <span style="color:red"><b>rotes</b></span> Icon gekennzeichnet, damit du nicht mit anderen Buttons durcheinander kommst: 
					<a href='javascript:void(0)'><span class='glyphicon glyphicon-user_add' style='color:red;'></span></a>
					<a href='javascript:void(0)'><span class='glyphicon glyphicon-user_remove' style='color:red;'></span></a>
					<br/>
					Sobald du die Admin Tools aktivierst, hast du an jeder einzelnen Schicht die Möglichkeit Veränderungen vorzunehmen.<br/>
					Vorerst wird das sein:<br/>
					<br/>
					<ul>
						<li>Löschen von Verkündigern aus einer Schicht</li>
						<li>Eintragen von Verkündigern in eine Schicht</li>
						<li>Im Kalender zurückspringen um frühere Wochen einzusehen</li>		
					</ul>
					
					<?php if($admintools) { ?>
						<div class="col-sm-4 col-md-3"  style="margin-top:20px;margin-bottom:20px;">
							<div class='input-group date'>
								<span class="input-group-addon datepickerbutton">
									<span class="glyphicon glyphicon-calendar"></span>
								</span>
								<?php 
									echo $this->Form->input('reservationStartDate', array('div' => false, 'label'=>false, 'class' => 'form-control', 'id' => 'reservationStartDate', 'type' => 'text', 'placeholder' => 'Schichten anzeigen ab dem')); 
								?>	
								<span class="input-group-addon" style="background-color: #5cb85c">
									<?php 
										echo "<a href='javascript:void(0)' onclick='setReservationStartDate(\"" .  $routeId . "\")' style='text-decoration: none; color: white'>Schichtplan anzeigen</a>";
									?>
								</span>						
							</div>		
						</div>				
					 <?php } ?>
					
					<br clear="all"/>
					<div class="col-sm-8 col-md-6">
						<?php if(!$admintools) {
							echo $this->Html->link('<button type="button" class="btn btn-success">Admin Tools aktivieren</span></button>', array('controller' => 'reservations', 'action' => 'toggleAdminTools', $routeId), array('escape' => false, 'title' => 'Admin Tools aktivieren'));
						} else {
							echo $this->Html->link('<button type="button" class="btn btn-warning">Admin Tools deaktivieren</span></button>', array('controller' => 'reservations', 'action' => 'toggleAdminTools', $routeId), array('escape' => false, 'title' => 'Admin Tools deaktivieren'));
						} ?>
					</div>
					
					
				</div>
			</div>
		</div>
		<?php
		}
		foreach ($routes as $route) {
			if($routeId == $route['Routes']['id']) {
				if($route['Routes']['description'] || glob("img/routes/route_" . $route['Routes']['id'] . ".*")) {
		?>
		<div class="panel panel-warning">
			<div class="panel-heading">
				<h4 class="panel-title">
					<a data-toggle="collapse" href="#collapse_route">
						<span style="font-size: 1.5em; margin-top: -5px;" class="glyphicon glyphicon-expand"></span>
						Beschreibung 
						<?php if (glob("img/routes/route_" . $route['Routes']['id'] . ".*") || $route['Routes']['maplink'] != "") { ?>
							& Karte 
						<?php } ?>
						der Route
					</a>
				</h4>
			</div>
			<div id="collapse_route" class="panel-collapse collapse">
				<div class="panel-body">
					<?php
						echo nl2br($route['Routes']['description']) . "<br/><br/>";
						if (glob("img/routes/route_" . $route['Routes']['id'] . ".*")) { 
							$file = glob('img/routes/route_' . $route['Routes']['id'] . '.*');
							echo '<button data-toggle="modal" data-target="#routeModal" type="button" data-data="/' . $file[0] . '" class="open-RouteDialog btn btn-warning">';
							echo 'Karte';
							echo '</button>';
						}	
						
						if ($route['Routes']['maplink'] != "") {
							echo '<a class="btn btn-warning" target="_blank" href="' . $route['Routes']['maplink'] . '">Karte</a>';
						}												
					?>
				</div>
			</div>
		</div>
		<?php
				}
			}
		}
		
		echo $this->element('lkwnumber');
		
		echo $this->element('ship');
		
		echo $this->element('week_iteration', array(
			'displaySizes' => array('lg')
		));	
		
		echo $this->element('week_iteration', array(
				'displaySizes' => array('sm', 'md')
		));

		echo $this->element('week_iteration', array(
			'displaySizes' => array('xs')
		));
		?>

		<!-- Guest Modal -->
		<div class="modal fade" id="guestModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
			 aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
								class="sr-only">Close</span></button>
						<h4 class="modal-title" id="myModalLabel">Verkündiger eintragen</h4>
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


		<!-- Delete Modal -->
		<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
			 aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
								class="sr-only">Close</span></button>
						<h4 class="modal-title" id="myModalLabel">Schicht löschen</h4>
					</div>
					<div class="modal-body" id="deleteModalDiv">
						Möchtest du wirklich diese Schicht löschen?
						<div id="hiddenParams">

						</div>
						<div class="checkbox" id="partnerCheckboxes">

						</div>
					</div>
					<div class="modal-footer" id="deleteModalBody">
						<div class="btn-group">
							<button type="button" class="btn btn-default" data-dismiss="modal">Schließen</button>
							<a href="javascript:void(0)" class="btn btn-danger" onclick="deletePublisher();">Löschen</a>
						</div>
					</div>
				</div>
			</div>
		</div>

	<?php
	}
?>

	<!-- Routes Modal -->
	<div class="modal fade" id="routeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
		 aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
							class="sr-only">Close</span></button>
					<h4 class="modal-title" id="myModalLabel">Karte der Route</h4>
				</div>
				<div class="modal-body">
					<img src="" id="route" alt="Map" class="img-rounded col-xs-12"><br/>
				</div>
				<div class="modal-footer">
					<div class="btn-group">
						<button type="button" class="btn btn-default" data-dismiss="modal">Schließen</button>
					</div>
				</div>
			</div>
		</div>
	</div>
		
<?php echo $this->element('report_modal', array('controller' => 'reservations'));?>

<?php echo $this->element('report_necessary_modal', array('controller' => 'reservations'));?>

<?php echo $this->element('lkwnumber_modal');?>

<?php echo $this->element('ship_modal');?>
