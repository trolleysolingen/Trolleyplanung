<div class="publishers index">
	<br/>
	<legend><?php echo __('Verkündiger der Versammlung ' . $publisher['Congregation']['name']); ?></legend>

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
				Hier findest du alle Verkündiger aufgelistet, mit denen du dich in deiner Versammlung für den Trolleydienst verabreden kannst.
			</div>
		</div>
	</div>

	<br/>
	<?php
		if ($publisher['Role']['name'] == 'admin' || $publisher['Role']['name'] == 'congregation admin') {
	?>
		<p class="actions">
			<?php echo $this->Html->link('<button type="button" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Neuen Verkündiger anlegen</button>', array('action' => 'add'), array('escape' => false)); ?>
			<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
				<span class="glyphicon glyphicon-message_full"></span> Alle Zugangsdaten verschicken
			</button>
		</p>
		<br/>
	<?php
		}
	?>


	<div class="table-responsive">
		<table width="100%" cellpadding="0" cellspacing="0" class="table table-striped table-hover">
			<thead>
				<tr>
					<?php
						if ($publisher['Role']['name'] == 'admin' || $publisher['Role']['name'] == 'congregation admin') {
					?>
							<th width="30%"><?php echo $this->Paginator->sort('email', 'Email'); ?></th>
							<th width="15%"><?php echo $this->Paginator->sort('surname', 'Nachname'); ?></th>
							<th width="15%"><?php echo $this->Paginator->sort('prename', 'Vorname'); ?></th>
							<th width="15%"><?php echo $this->Paginator->sort('phone', 'Telefon'); ?></th>
							<th width="25%" class="actions"><?php echo __('Aktionen'); ?></th>
					<?php
						} else {
					?>
							<th width="50%"><?php echo $this->Paginator->sort('surname', 'Nachname'); ?></th>
							<th width="50%"><?php echo $this->Paginator->sort('prename', 'Vorname'); ?></th>
					<?php
						}
					?>
				</tr>
			</thead>

			<tbody>

				<?php foreach ($publishers as $publisherItem): ?>
					<tr>
						<?php
							if ($publisher['Role']['name'] == 'admin' || $publisher['Role']['name'] == 'congregation admin') {
						?>
							<td><?php
									echo h($publisherItem['Publisher']['email']);
									if ($publisherItem['Role']['id'] == 2 || $publisherItem['Role']['id'] == 4) {
										echo " (Admin)";
									}
								?>&nbsp;</td>
						<?php
							}
						?>

						<td><?php echo h($publisherItem['Publisher']['surname']); ?>&nbsp;</td>
						<td><?php echo h($publisherItem['Publisher']['prename']); ?>&nbsp;</td>

						<?php
							if ($publisher['Role']['name'] == 'admin' || $publisher['Role']['name'] == 'congregation admin') {
						?>
							<td><?php echo h($publisherItem['Publisher']['phone']); ?>&nbsp;</td>

							<td class="actions" style="white-space:nowrap;">
								<?php
									echo $this->Html->link('<button type="button" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-pencil"></span></button>', array('action' => 'edit', $publisherItem['Publisher']['id']), array('escape' => false, 'title' => 'Bearbeiten'));
									echo $this->Html->link('<button type="button" data-data="' . $publisherItem['Publisher']['prename'] . ' ' . $publisherItem['Publisher']['surname'] . '" class="open-DeleteDialog btn btn-default btn-xs"><span class="glyphicon glyphicon-remove"></span></button>', '#', array('data-toggle'=> 'modal', 'data-target' => '#ConfirmDelete', 'data-action'=> Router::url(array('action'=>'delete',$publisherItem['Publisher']['id'])), 'escape' => false), false);
									if ($publisherItem['Publisher']['email'] && $publisherItem['Publisher']['email'] != "") {
										echo $this->Html->link('<button type="button" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-message_out"></span></button>', array('action' => 'sendAccount', $publisherItem['Publisher']['id']), array('escape' => false, 'title' => 'Zugangsdaten versenden'));
									}
								?>
							</td>
						<?php
							}
						?>
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

<!-- Confirmation Script to get id to delete -->
<?php $this->addScript('testscript', "$('#ConfirmDelete').on('show.bs.modal', function(e) {
    $(this).find('form').attr('action', $(e.relatedTarget).data('action'));
});"); ?>