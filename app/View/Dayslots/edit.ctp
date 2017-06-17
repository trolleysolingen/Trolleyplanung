<div class="congregations form">
<?php echo $this->Form->create('Dayslot', array('class' => 'form-horizontal')); ?>
	<fieldset>
		<div class="col-md-6 col-xs-12">
			<legend><?php echo __('Verfügbare Tage festlegen für Route ') . '"' . $route['Route']['name'] . '"'; ?></legend>
			<div class="panel panel-primary" style="padding:20px;">
			  <?php
				  echo $this->Form->input('id');
				  echo $this->Form->input('congregation_id', array(
					  'type' => 'hidden',
					  'value' => $publisher['Congregation']['id']
				  ));
				  echo $this->Form->input('route_id', array('type' => 'hidden'));
			  ?>
				<legend></legend>
				<div class="form-group">
					<label for="start" class="col-xs-4 control-label">Montag:</label>
					<div class="col-xs-8">
						<?php
						echo $this->Form->input('monday', array('type' => 'checkbox', 'label'=>false, 'id' => 'monday', 'style' => 'margin-left:0px;'));
						echo "&nbsp;&nbsp;";						
						echo $this->Html->link('<button type="button" class="btn btn-default btn-xs"><span>Schichtzeiten festlegen</span></button>', array('controller' => 'timeslots', 'action' => 'index', $routeId, 'day' => 'monday'), array('escape' => false, 'title' => 'Schichtzeiten festlegen'));						
						?>
					</div>
				</div>
				<div class="form-group">
					<label for="start" class="col-xs-4 control-label">Dienstag:</label>
					<div class="col-xs-8">
						<?php
						echo $this->Form->input('tuesday', array('type' => 'checkbox', 'label'=>false, 'id' => 'tuesday', 'style' => 'margin-left:0px;'));
						echo "&nbsp;&nbsp;";
						echo $this->Html->link('<button type="button" class="btn btn-default btn-xs"><span>Schichtzeiten festlegen</span></button>', array('controller' => 'timeslots', 'action' => 'index', $routeId, 'day' => 'tuesday'), array('escape' => false, 'title' => 'Schichtzeiten festlegen'));
						?>
					</div>
				</div>
				<div class="form-group">
					<label for="start" class="col-xs-4 control-label">Mittwoch:</label>
					<div class="col-xs-8">
						<?php
						echo $this->Form->input('wednesday', array('type' => 'checkbox', 'label'=>false, 'id' => 'wednesday', 'style' => 'margin-left:0px;'));
						echo "&nbsp;&nbsp;";
						echo $this->Html->link('<button type="button" class="btn btn-default btn-xs"><span>Schichtzeiten festlegen</span></button>', array('controller' => 'timeslots', 'action' => 'index', $routeId, 'day' => 'wednesday'), array('escape' => false, 'title' => 'Schichtzeiten festlegen'));
						?>
					</div>
				</div>
				<div class="form-group">
					<label for="start" class="col-xs-4 control-label">Donnerstag:</label>
					<div class="col-xs-8">
						<?php
						echo $this->Form->input('thursday', array('type' => 'checkbox', 'label'=>false, 'id' => 'thursday', 'style' => 'margin-left:0px;'));
						echo "&nbsp;&nbsp;";
						echo $this->Html->link('<button type="button" class="btn btn-default btn-xs"><span>Schichtzeiten festlegen</span></button>', array('controller' => 'timeslots', 'action' => 'index', $routeId, 'day' => 'thursday'), array('escape' => false, 'title' => 'Schichtzeiten festlegen'));
						?>
					</div>
				</div>
				<div class="form-group">
					<label for="start" class="col-xs-4 control-label">Freitag:</label>
					<div class="col-xs-8">
						<?php
						echo $this->Form->input('friday', array('type' => 'checkbox', 'label'=>false, 'id' => 'friday', 'style' => 'margin-left:0px;'));
						echo "&nbsp;&nbsp;";
						echo $this->Html->link('<button type="button" class="btn btn-default btn-xs"><span>Schichtzeiten festlegen</span></button>', array('controller' => 'timeslots', 'action' => 'index', $routeId, 'day' => 'friday'), array('escape' => false, 'title' => 'Schichtzeiten festlegen'));
						?>
					</div>
				</div>
				<div class="form-group">
					<label for="start" class="col-xs-4 control-label">Samstag:</label>
					<div class="col-xs-8">
						<?php
						echo $this->Form->input('saturday', array('type' => 'checkbox', 'label'=>false, 'id' => 'saturday', 'style' => 'margin-left:0px;'));
						echo "&nbsp;&nbsp;";
						echo $this->Html->link('<button type="button" class="btn btn-default btn-xs"><span>Schichtzeiten festlegen</span></button>', array('controller' => 'timeslots', 'action' => 'index', $routeId, 'day' => 'saturday'), array('escape' => false, 'title' => 'Schichtzeiten festlegen'));
						?>
					</div>
				</div>
				<div class="form-group">
					<label for="start" class="col-xs-4 control-label">Sonntag:</label>
					<div class="col-xs-8">
						<?php
						echo $this->Form->input('sunday', array('type' => 'checkbox', 'label'=>false, 'id' => 'sunday', 'style' => 'margin-left:0px;'));
						echo "&nbsp;&nbsp;";
						echo $this->Html->link('<button type="button" class="btn btn-default btn-xs"><span>Schichtzeiten festlegen</span></button>', array('controller' => 'timeslots', 'action' => 'index', $routeId, 'day' => 'sunday'), array('escape' => false, 'title' => 'Schichtzeiten festlegen'));
						?>
					</div>
				</div>
			  </div>
			  <?php echo $this->Form->end(array('label' => 'Speichern', 'class' => 'btn btn-primary btn-block', 'name' => 'editSubmit')); ?>
			</div>
		</div>

	</fieldset>
</div>
