<div class="routes index">
	<br/>
	<legend><?php echo __('Routen der Versammlung ' . $publisher['Congregation']['name']); ?></legend>

	<p class="actions">
		<?php echo $this->Html->link('<button type="button" class="btn btn-success"><span class="glyphicon glyphicon-plus" style="margin-top: -6px;"></span> Neue Route anlegen</button>', array('action' => 'add'), array('escape' => false)); ?>
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
								echo $this->Html->link('<button type="button" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-pencil"></span></button>', array('action' => 'edit', $route['Route']['id']), array('escape' => false, 'title' => 'Bearbeiten'));
								echo "&nbsp;&nbsp;";
								echo $this->Html->link('<button type="button" class="btn btn-default btn-xs">Tage festlegen</span></button>', array('controller' => 'dayslots', 'action' => 'index', $route['Route']['id']), array('escape' => false, 'title' => 'Tage festlegen'));
								echo "&nbsp;&nbsp;";
								echo $this->Html->link('<button type="button" class="btn btn-default btn-xs">Schichtzeiten festlegen</span></button>', array('controller' => 'timeslots', 'action' => 'index', $route['Route']['id']), array('escape' => false, 'title' => 'Schichtzeiten festlegen'));
								if(glob("img/routes/route_" . $route['Route']['id'] . ".*")) { 
									echo "&nbsp;&nbsp;";
									$file = glob('img/routes/route_' . $route['Route']['id'] . '.*');
									echo '<button data-toggle="modal" data-target="#routeModal" type="button" data-data="' . $file[0] . '" class="open-RouteDialog btn btn-warning btn-xs">';
									echo 'Karte anzeigen';
									echo '</button>';
									echo "&nbsp;&nbsp;";
									echo $this->Html->link('<button type="button" data-data="' . $route['Route']['name'] . '" class="open-Dialog btn btn-danger btn-xs">Karte löschen</button>', '#', array('data-toggle'=> 'modal', 'data-target' => '#ConfirmDelete', 'data-action'=> Router::url(array('action'=>'deleteMap',$route['Route']['id'])), 'escape' => false), false);
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