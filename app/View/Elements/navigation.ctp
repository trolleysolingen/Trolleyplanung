<?php
	$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$linkarray = explode("/", $actual_link);
	
	/* CHECK if Link Mitarbeiter is active */
	if(in_array("reservations", $linkarray)) {
		$reservations = "active";
	}
	else {
		$reservations = "";
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
						<li class="<?php echo $reservations ?>">
							<?php echo $this->Html->link('Schichten', array('controller' => 'reservations', 'action' => 'index')); ?>				
						</li>
					</ul>
					<ul class="nav navbar-nav navbar-right" style="margin-right: 20px;">
						<p class="navbar-text hidden-xs">
							<?php echo $publisher['Publisher']['prename'] . ' '. $publisher['Publisher']['surname'] . ' - ' . $congregation["Congregation"]["name"] ?>
						</p>
						
						<li class="hidden-xs">
							<?php echo $this->Html->link('<span class="glyphicon glyphicon-exit"></span>', array('controller' => 'VS-' . $congregation["Congregation"]["path"] . '/reservations', 'action' => 'logout'), array('escape' =>false)); ?>
						</li>
						<li class="visible-xs-block">
							<?php
							echo $this->Html->link(
								$this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-exit')) . "  Abmelden",
								array('controller' => 'VS-' . $congregation["Congregation"]["path"] . '/reservations', 'action' => 'logout'),
								array('escape' => false)
							); ?>
						</li>
					</ul>
			<?php
				} else { 
			?>
				<div class="hidden-xs">
					<?php
					echo $this->Form->create(null, array('class' => 'navbar-form navbar-right', 'style' => 'margin-right: 20px;', 
						'url' => array('controller' => 'VS-' . $congregation["Congregation"]["path"])
					)); ?>
					<div class="form-group">
						<?php
							echo $this->Form->input('email', array('div' => false, 'label'=>false, 'class' => 'form-control', 'placeholder' => 'Email', 'id' => 'email'));
						?>
					</div>
					<?php echo $this->Form->end(array('div' => false, 'label' => 'Anmelden', 'class' => 'btn btn-primary')); ?>
				</div>
			<?php } ?>
		</div><!--/.nav-collapse -->
	</nav>