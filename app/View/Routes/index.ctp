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
        <h4 class="modal-title" id="myModalLabel">Verkündiger löschen</h4>
      </div>
      <div class="modal-body">
        Möchtest du den Verkündiger <b><div id="data" name="data"></div></b> wirklich löschen?
      </div>
      <div class="modal-footer">
		<div class="btn-group">
			<button type="button" class="btn btn-default" data-dismiss="modal">Nein</button>
			<?php
			echo $this->Form->postLink(
					'Ja',
					array('action' => 'delete'),
					array('class' => 'btn btn-danger'),
					false
				);
			?>
		</div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel2">Zugangsdaten verschicken</h4>
      </div>
      <div class="modal-body">
        Bist du dir sicher, dass du allen Verkündigern die Zugangsdaten per Mail schicken möchtest?
      </div>
      <div class="modal-footer">
        <div class="btn-group">
			<button type="button" class="btn btn-default" data-dismiss="modal">Nein</button>
			<?php
			echo $this->Form->postLink(
					'Ja',
					array('action' => 'sendMultiAccounts'),
					array('class' => 'btn btn-success'),
					false
				);
			?>
		</div>
      </div>
    </div>
  </div>
</div>