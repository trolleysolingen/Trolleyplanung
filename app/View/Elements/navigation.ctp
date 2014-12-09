<?php
	$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$linkarray = explode("/", $actual_link);

	$highlightReservations = "";
	$highlightMessages = "";
	$highlightPublishers = "";
	$highlightTimeslots = "";
	$highlightMyCongregation = "";
	$highlightCongregationSettings = "";
	$highlightCongregations = "";
	$highlightContact = "";

	if (in_array("reservations", $linkarray)) {
		$highlightReservations = "active";
	} else if (in_array("publishers", $linkarray)) {
		$highlightPublishers = "active";
	} else if (in_array("timeslots", $linkarray)) {
		$highlightTimeslots = "active";
	} else if (in_array("congregations", $linkarray) && in_array("edit", $linkarray)) {
		$highlightCongregationSettings = "active";
	} else if (in_array("congregations", $linkarray)) {
		$highlightCongregations = "active";
	} else if (in_array("contact", $linkarray)) {
		$highlightContact = "active";
	} else if (in_array("messages", $linkarray)) {
		$highlightMessages = "active";
	}
	
	if ($highlightCongregationSettings == "active" || $highlightPublishers == "active" || $highlightTimeslots == "active" || $highlightCongregations == "active" || $highlightMessages == "active") {
		$highlightMyCongregation = "active";
	}
	
?>
	
	<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<?php 
				echo $this->Html->link(
					$this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-grater jw-tower')) . "Trolleyverwaltung",
					array('controller' => 'start', 'action' => 'index'),
					array('class' => 'navbar-brand', 'escape' => false)
				);
			?>
		</div>
		<div class="collapse navbar-collapse">
			<?php
				$publisher = $this->Session->read('publisher');
				if ($publisher) { ?>
					<ul class="nav navbar-nav">
						<li class="<?php echo $highlightReservations ?>">
							<?php echo $this->Html->link('Schichten', array('controller' => 'reservations', 'action' => 'index')); ?>
						</li>
						<?php
							if ($publisher['Role']['name'] == 'publisher') {
						?>
								<li class="<?php echo $highlightPublishers ?>">
									<?php echo $this->Html->link('Verkündiger', array('controller' => 'publishers', 'action' => 'index')); ?>
								</li>
						<?php
							}
							if ($publisher['Role']['name'] == 'admin' || $publisher['Role']['name'] == 'congregation admin') {
						?>
						
						<li class="dropdown <?php echo $highlightMyCongregation ?>">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Meine Versammlung 
								<span class="caret"></span>
							</a>
							<ul class="dropdown-menu" role="menu">
								<li class="<?php echo $highlightMessages ?>">
									<?php echo $this->Html->link('Neue Nachricht', array('controller' => 'messages', 'action' => 'index')); ?>
								</li>
								<li class="divider"></li>
								<li class="<?php echo $highlightCongregationSettings ?>">
									<?php echo $this->Html->link('Einstellungen', array('controller' => 'congregations', 'action' => 'edit', $publisher['Congregation']['id'])); ?>
								</li>
								<li class="<?php echo $highlightPublishers ?>">
									<?php echo $this->Html->link('Verkündiger', array('controller' => 'publishers', 'action' => 'index')); ?>
								</li>
								<li class="<?php echo $highlightTimeslots ?>">
									<?php echo $this->Html->link('Schichtzeiten', array('controller' => 'timeslots', 'action' => 'index')); ?>
								</li>
								<?php
									if ($publisher['Role']['name'] == 'admin') {
								?>
									<li class="divider"></li>
									<li class="<?php echo $highlightCongregations ?>">
										<?php echo $this->Html->link('Alle Versammlungen', array('controller' => 'congregations', 'action' => 'index')); ?>
									</li>
								<?php
									}
								?>
						  </ul>
						</li>
						<?php
							}
						?>
						<li class="<?php echo $highlightContact ?>">
							<?php echo $this->Html->link('Kontakt', array('controller' => 'contact', 'action' => 'index')); ?>
						</li>
					</ul>
					<ul class="nav navbar-nav navbar-right" style="margin-right: 20px;">
						<p class="navbar-text hidden-xs hidden-sm">
							<?php echo $publisher['Publisher']['prename'] . ' '. $publisher['Publisher']['surname'] ?>
						</p>
						
						<li class="hidden-xs">
							<?php echo $this->Html->link('<span class="glyphicon glyphicon-exit"></span>', array('controller' => 'reservations', 'action' => 'logout'), array('escape' =>false)); ?>
						</li>
						<li class="visible-xs-block">
							<?php
							echo $this->Html->link(
								$this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-exit')) . "  Abmelden",
								array('controller' => 'reservations', 'action' => 'logout'),
								array('escape' => false)
							); ?>
						</li>
					</ul>
			<?php
				} else { 
			?>
			
				<ul class="nav navbar-nav">
					<li class="<?php echo $highlightContact ?>">
						<?php echo $this->Html->link('Impressum', array('controller' => 'contact', 'action' => 'index')); ?>
					</li>
				</ul>
				<div class="hidden-xs hidden-sm">
					<?php
					echo $this->Form->create('Start', array('class' => 'navbar-form navbar-right', 'style' => 'margin-right: 20px;',
						'url' => array('controller' => 'start')
					)); ?>
					<div class="form-group">
						<?php
							echo $this->Form->input('email', array('div' => false, 'label'=>false, 'class' => 'form-control', 'style' => 'margin-right:10px;', 'placeholder' => 'Email', 'id' => 'email'));
							echo $this->Form->input('password', array('div' => false, 'label'=>false, 'class' => 'form-control', 'placeholder' => 'Passwort', 'id' => 'password'));
						?>
					</div>
					<?php echo $this->Form->end(array('div' => false, 'label' => 'Anmelden', 'class' => 'btn btn-primary')); ?>
				</div>
			<?php } ?>
		</div><!--/.nav-collapse -->
	</nav>