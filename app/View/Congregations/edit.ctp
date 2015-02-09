<div class="congregations form">
<?php echo $this->Form->create('Congregation', array('class' => 'form-horizontal')); ?>
	<fieldset>
		<div class="col-md-6 col-xs-12">
			<legend><?php echo __('Versammlung bearbeiten'); ?></legend>
			<div class="panel panel-primary" style="padding:20px;">
			  <?php
				echo $this->Form->input('id');
			  ?>
			  <div class="panel-body">
				<div class="form-group">
					<label for="start" class="col-xs-4 control-label">Name:</label>
					<div class="col-xs-8">
						<?php
						echo $this->Form->input('name', array('label'=>false, 'class' => 'form-control', 'placeholder' => 'Name', 'id' => 'name'));
						?>
					</div>
				</div>
				<legend></legend>
				<div class="form-group">
					<label for="start" class="col-xs-4 control-label">Montag:</label>
					<div class="col-xs-8">
						<?php
						echo $this->Form->input('monday', array('type' => 'checkbox', 'label'=>false, 'id' => 'monday', 'style' => 'margin-left:0px;'));
						?>
					</div>
				</div>
				<div class="form-group">
					<label for="start" class="col-xs-4 control-label">Dienstag:</label>
					<div class="col-xs-8">
						<?php
						echo $this->Form->input('tuesday', array('type' => 'checkbox', 'label'=>false, 'id' => 'tuesday', 'style' => 'margin-left:0px;'));
						?>
					</div>
				</div>
				<div class="form-group">
					<label for="start" class="col-xs-4 control-label">Mittwoch:</label>
					<div class="col-xs-8">
						<?php
						echo $this->Form->input('wednesday', array('type' => 'checkbox', 'label'=>false, 'id' => 'wednesday', 'style' => 'margin-left:0px;'));
						?>
					</div>
				</div>
				<div class="form-group">
					<label for="start" class="col-xs-4 control-label">Donnerstag:</label>
					<div class="col-xs-8">
						<?php
						echo $this->Form->input('thursday', array('type' => 'checkbox', 'label'=>false, 'id' => 'thursday', 'style' => 'margin-left:0px;'));
						?>
					</div>
				</div>
				<div class="form-group">
					<label for="start" class="col-xs-4 control-label">Freitag:</label>
					<div class="col-xs-8">
						<?php
						echo $this->Form->input('friday', array('type' => 'checkbox', 'label'=>false, 'id' => 'friday', 'style' => 'margin-left:0px;'));
						?>
					</div>
				</div>
				<div class="form-group">
					<label for="start" class="col-xs-4 control-label">Samstag:</label>
					<div class="col-xs-8">
						<?php
						echo $this->Form->input('saturday', array('type' => 'checkbox', 'label'=>false, 'id' => 'saturday', 'style' => 'margin-left:0px;'));
						?>
					</div>
				</div>
				<div class="form-group">
					<label for="start" class="col-xs-4 control-label">Sonntag:</label>
					<div class="col-xs-8">
						<?php
						echo $this->Form->input('sunday', array('type' => 'checkbox', 'label'=>false, 'id' => 'sunday', 'style' => 'margin-left:0px;'));
						?>
					</div>
				</div>
			  </div>
			  <?php echo $this->Form->end(array('label' => 'Speichern', 'class' => 'btn btn-primary btn-block', 'name' => 'editSubmit')); ?>
			</div>
		</div>
		
		<div class="col-md-6 col-xs-12">
			<legend><?php echo __('Module verwalten'); ?></legend>
			<div class="panel panel-primary">
			  <div class="panel-body">
				<div class="col-sm-6 col-xs-12" style="margin-bottom:10px;">
					<a href='javascript:void(0)' style="margin-right:10px;" data-toggle="modal" data-target="#myKeyModal"><span style="margin-top:-5px;" class='glyphicon glyphicon-circle_info'></span></a>
					Schlüsselverwaltung: 
					<?php
						if($publisher['Congregation']['key_management']) {
							echo "<span style='color:#5cb85c'>aktiv</span>";
						} else {
							echo "<span style='color:#d9534f'>inaktiv</span>";
						}
					?>
				</div>
				
				<div class="col-sm-6 col-xs-12" style="padding:10px;">
					<?php
						if($publisher['Congregation']['key_management']) {
							echo $this->Html->link('<button type="button" class="btn btn-danger btn-block" style="margin-top:-15px;">Deaktivieren</button>', array('action' => 'switchModuleStatus', $publisher['Congregation']['id'], 'key_management'), array('escape' => false, 'style' => 'text-decoration: none;'));
						} else {
							echo $this->Html->link('<button type="button" class="btn btn-success btn-block" style="margin-top:-15px;">Aktivieren</button>', array('action' => 'switchModuleStatus', $publisher['Congregation']['id'], 'key_management'), array('escape' => false, 'style' => 'text-decoration: none;'));
						}
					?>
				</div>
				
				<legend></legend>
				
				<div class="col-sm-6 col-xs-12" style="margin-bottom:10px;">
					<a href='javascript:void(0)' style="margin-right:10px;" data-toggle="modal" data-target="#myKeyModal"><span style="margin-top:-5px;" class='glyphicon glyphicon-circle_info'></span></a>
					Bericht: 
					<?php
						if($publisher['Congregation']['report']) {
							echo "<span style='color:#5cb85c'>aktiv</span><br/> Beginn: " . date("d.m.Y", strtotime($publisher['Congregation']['report_start_date']));
						} else {
							echo "<span style='color:#d9534f'>inaktiv</span>";
						}
					?>
				</div>
				
				<div class="col-sm-6 col-xs-12" style="padding:10px;">
					<?php
						if($publisher['Congregation']['report']) {
							echo $this->Html->link('<button type="button" class="btn btn-danger btn-block" style="margin-top:-15px;">Deaktivieren</button>', array('action' => 'switchModuleStatus', $publisher['Congregation']['id'], 'report'), array('escape' => false, 'style' => 'text-decoration: none;'));
						} else { ?>
								<div class='input-group date' style="margin-top:-15px;">
									<span class="input-group-addon datepickerbutton">
										<span class="glyphicon glyphicon-calendar"></span>
									</span>
									<?php 
										echo $this->Form->input('reportDate', array('div' => false, 'label'=>false, 'class' => 'form-control', 'id' => 'reportDate', 'type' => 'text', 'placeholder' => 'Ab dem')); 
									?>
									<span class="input-group-addon" style="background-color: #5cb85c">
										<?php 
											echo "<a href='javascript:void(0)' onclick='setReportDate(\"" . $publisher['Congregation']['id'] . "\")' style='text-decoration: none; color: white'>Aktivieren</a>";
										?>
									</span>
								</div>
						<?php
						}
					?>
				</div>
				
				<legend></legend>
			  </div>
			</div>
		</div>
	</fieldset>
</div>

<!-- Key Info Modal -->
<div class="modal fade" id="myKeyModal" tabindex="-1" role="dialog" aria-labelledby="myKeyModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myKeyModalLabel">Schlüsselverwaltung Info</h4>
      </div>
      <div class="modal-body">
		<p>Sobald du die Schlüsselverwaltung aktiviert hast, kann man zu jedem Verkündiger sehen ob er einen Saalschlüssel (um an den Trolley zu kommen) oder einen Schlüssel zum Trolleydepot hat. In der Schichtplanung sieht das dann wie folgt aus:</p>
		<img src="/img/help/key_reservation.jpg" alt="Reservation Key" class="img-rounded"><br/><br/>
		<p>Bei der Verkündigeranlage und Bearbeitung kannst du angeben ob ein Verkündiger einen Schlüssel besitzt. Außerdem kannst du auch schnell auf der globalen Verkündigerliste  angeben welche Verkündiger einen Schlüssel haben.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Schließen</button>
      </div>
    </div>
  </div>
</div>