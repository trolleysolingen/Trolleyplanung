<?php
	$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$linkarray = explode("/", $actual_link);

	$highlightReservations = "";
	$highlightReports = "";
	$highlightMessages = "";
	$highlightPublishers = "";
	$highlightRoutes = "";
	$highlightMyCongregation = "";
	$highlightCongregationSettings = "";
	$highlightCongregations = "";
	$highlightCongregationReports = "";
	$highlightCongregationStats = "";
	$highlightContact = "";
	$highlightTodos = "";
	$highlightSupport = "";
	$highlightMyTrolley = "";
	$highlightProfile = "";
	$highlightAdmin = "";

	if (in_array("reservations", $linkarray)) {
		$highlightReservations = "active";
	} else if (in_array("reports", $linkarray) && !in_array("admin", $linkarray)) {
		$highlightReports = "active";
	} else if (in_array("reports", $linkarray) && in_array("admin", $linkarray)) {
		$highlightCongregationReports = "active";
	} else if (in_array("stats", $linkarray)) {
		$highlightCongregationStats = "active";
	} else if (in_array("publishers", $linkarray)) {
		$highlightPublishers = "active";
	}else if ((in_array("congregations", $linkarray) && in_array("edit", $linkarray)) || in_array("routes", $linkarray) || in_array("dayslots", $linkarray) || in_array("timeslots", $linkarray)) {
		$highlightCongregationSettings = "active";
	} else if (in_array("congregations", $linkarray)) {
		$highlightCongregations = "active";
	} else if (in_array("contact", $linkarray)) {
		$highlightContact = "active";
	} else if (in_array("messages", $linkarray)) {
		$highlightMessages = "active";
	} else if (in_array("todos", $linkarray)) {
		$highlightTodos = "active";
	} else if (in_array("profile", $linkarray)) {
		$highlightProfile = "active";
	} else if (in_array("admin", $linkarray)) {
		$highlightAdmin = "active";
	} 
	
	if ($highlightCongregationSettings == "active" || $highlightPublishers == "active" || $highlightCongregations == "active" || $highlightMessages == "active" || $highlightCongregationReports == "active" || $highlightCongregationStats == "active") {
		$highlightMyCongregation = "active";
	} else if ($highlightTodos == "active" || $highlightContact == "active") {
		$highlightSupport = "active";
	} else if ($highlightReservations == "active" || $highlightReports == "active") {
		$highlightMyTrolley = "active";
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
				$adminReportNumber = $this->Session->read('adminReportNumber');
				$publisherReports = $this->Session->read('publisherReports');
				if ($publisher) { ?>
					<ul class="nav navbar-nav">
						<li class="dropdown <?php echo $highlightMyTrolley ?>">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><span class="glyphicon glyphicon-calendar" style="margin-right: 10px; margin-top: -6px;"></span><span class="hidden-sm">Mein Trolleydienst </span><span class="badge"><?php echo $publisherReports; ?></span>
								<span class="caret"></span>
							</a>
							<ul class="dropdown-menu" role="menu">
								<li class="<?php echo $highlightReservations ?>">
									<?php echo $this->Html->link('Schichten', array('controller' => 'reservations', 'action' => 'index')); ?>
								</li>
								<?php if ($publisher['Congregation']['report'] == 1 && $publisher['Congregation']['report_start_date'] <= date("Y-m-d")) { ?>
									<li class="<?php echo $highlightReports ?>">
										<?php echo $this->Html->link('Bericht <span class="badge">' . $publisherReports . '</span>', array('controller' => 'reports', 'action' => 'index'), array('escape' =>false)); ?>
									</li>
								<?php
								}
								?>
						  </ul>
						</li>
						
						<li class="dropdown <?php echo $highlightMyCongregation ?>">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><span class="glyphicon glyphicon-cogwheels" style="margin-right: 10px; margin-top: -6px;"></span><span class="hidden-sm">Meine Versammlung </span><span class="badge"><?php echo $adminReportNumber; ?></span>
								<span class="caret"></span>
							</a>
							<ul class="dropdown-menu" role="menu">
								<?php if ($publisher['Role']['name'] == 'admin' || $publisher['Role']['name'] == 'congregation admin') { ?>
									<li class="<?php echo $highlightMessages ?>">
										<?php echo $this->Html->link('Neue Nachricht', array('controller' => 'messages', 'action' => 'index')); ?>
									</li>
									<li class="divider"></li>
									<li class="<?php echo $highlightCongregationSettings ?>">
										<?php echo $this->Html->link('Einstellungen', array('controller' => 'congregations', 'action' => 'edit', $publisher['Congregation']['id'])); ?>
									</li>
								<?php
								}
								?>
									<li class="<?php echo $highlightPublishers ?>">
										<?php echo $this->Html->link('Verkündiger', array('controller' => 'publishers', 'action' => 'index')); ?>
									</li>
								<?php if ($publisher['Role']['name'] == 'admin' || $publisher['Role']['name'] == 'congregation admin') { ?>
									<?php if ($publisher['Congregation']['report'] == 1 && $publisher['Congregation']['report_start_date'] <= date("Y-m-d")) { ?>
										<li class="divider"></li>
										<li class="<?php echo $highlightCongregationReports ?>">
											<?php echo $this->Html->link('Bericht <span class="badge">' . $adminReportNumber . '</span>', array('controller' => 'reports', 'action' => 'admin'), array('escape' =>false)); ?>
										</li>
										<li class="<?php echo $highlightCongregationStats ?>">
											<?php echo $this->Html->link('Statistik', array('controller' => 'stats', 'action' => 'index'), array('escape' =>false)); ?>
										</li>
									<?php
									}
									?>
									<?php
										if ($publisher['Role']['name'] == 'admin') {
									?>
										<li class="divider"></li>
										<li class="<?php echo $highlightCongregations ?>">
											<?php echo $this->Html->link('Alle Versammlungen', array('controller' => 'congregations', 'action' => 'index')); ?>
										</li>
									<?php
									}
								}
								?>
						  </ul>
						</li>
						<li class="dropdown <?php echo $highlightSupport ?>">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><span class="glyphicon glyphicon-group" style="margin-right: 10px; margin-top: -6px;"></span><span class="hidden-sm">Support </span><span class="caret"></span>
							</a>
							<ul class="dropdown-menu" role="menu">
								<li class="<?php echo $highlightContact ?>">
									<?php echo $this->Html->link('Kontakt', array('controller' => 'contact', 'action' => 'index')); ?>
								</li>
								<?php
									if ($publisher['Role']['name'] == 'admin' || $publisher['Role']['name'] == 'congregation admin') {
								?>
								<li class="divider"></li>
								<li class="<?php echo $highlightTodos ?>">
									<?php echo $this->Html->link('Todos', array('controller' => 'todos', 'action' => 'index')); ?>
								</li>
								<?php
									}
								?>
						  </ul>
						</li>
						<?php if ($publisher['Role']['name'] == 'admin') { ?>
							<li class="<?php echo $highlightAdmin ?>">
								<?php echo $this->Html->link("<span class='glyphicon glyphicon-warning_sign' style='margin-right:10px; margin-top:-5px;'></span><span class='hidden-sm hidden-md'> Admin</span>", array('controller' => 'admin', 'action' => 'index'), array('escape' =>false)); ?>
							</li>
						<?php } ?>
					</ul>
					<ul class="nav navbar-nav navbar-right" style="margin-right: 20px;">
						<li class="<?php echo $highlightProfile ?>">
							<?php echo $this->Html->link("<span class='glyphicon glyphicon-wrench' style='margin-right:10px; margin-top:-5px;'></span><span class='hidden-sm hidden-md'>" . $publisher['Publisher']['prename'] . ' '. $publisher['Publisher']['surname'] . "</span><span class='visible-md-inline'>" . $publisher['Publisher']['prename'][0] . $publisher['Publisher']['surname'][0] . "</span>", array('controller' => 'profile', 'action' => 'index'), array('escape' =>false)); ?>
						</li>
						
						
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
						<?php echo $this->Html->link(
								$this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-group', 'style' => 'margin-right: 5px; margin-top: -6px;')) . "  Impressum",
								array('controller' => 'contact', 'action' => 'index'),
								array('escape' => false)
						);?>
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