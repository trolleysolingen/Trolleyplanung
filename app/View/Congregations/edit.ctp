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
					<a href='javascript:void(0)' style="margin-right:10px;" data-toggle="modal" data-target="#guestsModal"><span style="margin-top:-5px;" class='glyphicon glyphicon-circle_info'></span></a>
					Gast-Einträge:
					<?php
						if($publisher['Congregation']['guests_not_allowed']) {
							echo "<span style='color:#d9534f'>nicht erlaubt</span>";
						} else {
							echo "<span style='color:#5cb85c'>erlaubt</span>";
						}
					?>
				</div>

				<div class="col-sm-6 col-xs-12" style="padding:10px;">
					<?php
						if($publisher['Congregation']['guests_not_allowed']) {
							echo $this->Html->link('<button type="button" class="btn btn-success btn-block" style="margin-top:-15px;">Erlauben</button>', array('action' => 'switchModuleStatus', $publisher['Congregation']['id'], 'guests_not_allowed'), array('escape' => false, 'style' => 'text-decoration: none;'));
						} else {
							echo $this->Html->link('<button type="button" class="btn btn-danger btn-block" style="margin-top:-15px;">Nicht erlauben</button>', array('action' => 'switchModuleStatus', $publisher['Congregation']['id'], 'guests_not_allowed'), array('escape' => false, 'style' => 'text-decoration: none;'));
						}
					?>
				</div>
				
				<legend></legend>
				
				<div class="col-sm-6 col-xs-12" style="margin-bottom:10px;">
					<a href='javascript:void(0)' style="margin-right:10px;" data-toggle="modal" data-target="#reportModal"><span style="margin-top:-5px;" class='glyphicon glyphicon-circle_info'></span></a>
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
			  </div>
			</div>
		</div>
	</fieldset>
</div>
<br/>
<br/>
<div>
	<br/>
	<legend><?php echo __('Routen der Versammlung ' . $publisher['Congregation']['name']); ?></legend>

	<p class="actions">
		<?php echo $this->Html->link('<button type="button" class="btn btn-success"><span class="glyphicon glyphicon-plus" style="margin-top: -6px;"></span> Neue Route anlegen</button>', array('controller' => 'routes', 'action' => 'add'), array('escape' => false)); ?>
	</p>

	<div class="table-responsive">
		<table width="100%" cellpadding="0" cellspacing="0" class="table table-striped table-hover">
			<thead>
				<tr>
					<th width="40%"><?php echo $this->Paginator->sort('name', 'Name'); ?></th>
					<th width="60%" class="actions"><?php echo __('Aktionen'); ?></th>
				</tr>
			</thead>

			<tbody>

				<?php foreach ($routes as $route): ?>
					<tr>
						<td><?php echo h($route['Route']['name']); ?>&nbsp;</td>


						<td class="actions" style="white-space:nowrap;">
							<?php
								echo $this->Html->link('<button type="button" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-pencil"></span></button>', array('controller' => 'routes', 'action' => 'edit', $route['Route']['id']), array('escape' => false, 'title' => 'Bearbeiten'));
								echo "&nbsp;&nbsp;";
								echo $this->Html->link('<button type="button" class="btn btn-default btn-xs">Tage festlegen</span></button>', array('controller' => 'dayslots', 'action' => 'index', $route['Route']['id']), array('escape' => false, 'title' => 'Tage festlegen'));
								echo "&nbsp;&nbsp;";
								echo $this->Html->link('<button type="button" class="btn btn-default btn-xs">Schichtzeiten festlegen</span></button>', array('controller' => 'timeslots', 'action' => 'index', $route['Route']['id']), array('escape' => false, 'title' => 'Schichtzeiten festlegen'));
								if(glob("img/routes/route_" . $route['Route']['id'] . ".*")) { 
									echo "&nbsp;&nbsp;";
									$file = glob('img/routes/route_' . $route['Route']['id'] . '.*');
									echo '<button data-toggle="modal" data-target="#routeModal" type="button" data-data="/' . $file[0] . '" class="open-RouteDialog btn btn-warning btn-xs">';
									echo 'Karte anzeigen';
									echo '</button>';
									echo "&nbsp;&nbsp;";
									echo $this->Html->link('<button type="button" data-data="' . $route['Route']['name'] . '" class="open-Dialog btn btn-danger btn-xs">Karte löschen</button>', '#', array('data-toggle'=> 'modal', 'data-target' => '#ConfirmDelete', 'data-action'=> Router::url(array('controller' => 'routes', 'action'=>'deleteMap',$route['Route']['id'])), 'escape' => false), false);
								} else {									
									echo "&nbsp;&nbsp;";
									echo '<button data-toggle="modal" data-target="#uploadMap" type="button" data-data="' . $route['Route']['id'] . '" class="open-UploadDialog btn btn-default btn-xs">';
									echo 'Karte hinzufügen';
									echo '</button>';
								}
							?>
						</td>

					</tr>
				<?php endforeach; ?>

			</tbody>


		</table>
	</div>

	<ul class="pagination pagination-large">
		<?php
		echo $this->Paginator->prev(__('Zurück'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
		echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'currentClass' => 'active','tag' => 'li','first' => 1));
		echo $this->Paginator->next(__('Nächste'), array('tag' => 'li','currentClass' => 'disabled'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
		?>
	</ul>

	<div>
		<?php
			$paginatorParams = $this->Paginator->params();
			echo "Insgesamt: " . $paginatorParams['count'];

			echo $this->Form->end();
		?>
	</div>
</div>


<!-- Modal -->
<div class="modal fade" id="ConfirmDelete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Karte löschen</h4>
      </div>
      <div class="modal-body">
        Möchtest du die Karte zur Route <b><div id="data" name="data"></div></b> wirklich löschen?
      </div>
      <div class="modal-footer">
		<div class="btn-group">
			<button type="button" class="btn btn-default" data-dismiss="modal">Nein</button>
			<?php
			echo $this->Form->postLink(
					'Ja',
					array('action' => 'deleteMap'),
					array('class' => 'btn btn-danger'),
					false
				);
			?>
		</div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="uploadMap" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Karte hochladen</h4>
      </div>
      <div class="modal-body">
		<?php echo $this->Form->create('Files', array('class' => 'form-horizontal', 'url' => '/routes/uploadMap', 'type' => 'file')); ?>
		<fieldset>
			<?php
				echo $this->Form->input('id', array('type' => 'hidden', 'label'=>false, 'class' => 'form-control', 'id' => 'id'));
			?>
		
			<div class="form-group">
				<label for="start" class="col-sm-4 control-label">Datei:</label>
				<div class="col-sm-8">
					<?php
						echo $this->Form->input('Files.upload', array('type' => 'file', 'label'=>false, 'class' => 'form-control', 'id' => 'file'));
					?>
				</div>
			</div>

		</fieldset>

		
      </div>
      <div class="modal-footer">
		<div class="btn-group">
			<button type="button" class="btn btn-default" data-dismiss="modal">Schließen</button>
			<?php echo $this->Form->end(array('label' => 'Hochladen', 'class' => 'btn btn-primary', 'div' => false)); ?>
		</div>
      </div>
    </div>
  </div>
</div>

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

<!-- guestsModal -->
<div class="modal fade" id="guestsModal" tabindex="-1" role="dialog" aria-labelledby="guestsModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="guestsModalLabel">Gäste nicht erlauben Info</h4>
      </div>
      <div class="modal-body">
		<p>
			Hier kannst du einstellen, ob die Verkündiger deiner Versammlung auch Gäste als Partner eintragen können.
			Sind Gäste zugelassen, so bekommst du als Versammlungsadministrator stets eine Mail, wenn ein Verkündiger einen Gast einträgt.
		</p>
		<b>So sieht es aus, wenn Gäste nicht erlaubt sind und der Verkündiger versucht einen solchen einzutragen:</b><br/><br/>
		<img src="/img/help/guests_not_allowed.jpg" alt="Guests not allowed" width="560px;" class="img-rounded"><br/>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Schließen</button>
      </div>
    </div>
  </div>
</div>

<!-- Key Info Modal -->
<div class="modal fade" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="myReportModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myReportModalLabel">Bericht Info</h4>
      </div>
      <div class="modal-body">
		<p>
			Wenn du den Bericht für deine Versammlung aktivierst, musst du zuerst ein Datum angeben zu dem der Bericht aktiv sein soll. Ab diesem Datum werden für den Verkündiger, der sich in eine Schicht einträgt und den Partner ein Button pro Schicht sichtbar. Mit dem Klick auf diesen, können beide den Bericht für die Schicht abgeben. Daher sollten sie sich vorher absprechen, wer von beiden den Bericht abgeben will.
		</p>
		<p>
			Ein Verkündiger kann einen Bericht auch ablehnen. Diese Berichte siehst du zusammen mit den noch nicht abgegebenen Berichten unter dem Reiter "Meine Versammlung" -> "Bericht". Du kannst dort die Ablehnung entweder akzeptieren oder dem Verkündiger zur erneuten Abgabe zurück geben. Für noch nicht abgegebene Berichte kannst du Errinnerungsmails losschicken.
		</p>
		<p>
			Unter dem Reiter "Meine Versammlung" -> "Statistik" siehst du die aktuelle Monatsstatistik. Sollte der Bericht schon mehr als ein Monat laufen, kannst du den Monat auswählen. Solltest du noch weitere Statistikwünsche haben, mache doch dafür ein Todo auf.
		</p>
		<b>So sieht ein Teil der Statistik aus:</b><br/><br/>
		<img src="/img/help/stats.jpg" alt="Guests not allowed" class="img-rounded"><br/>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Schließen</button>
      </div>
    </div>
  </div>
</div>