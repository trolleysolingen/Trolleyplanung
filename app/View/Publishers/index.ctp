<script type="text/javascript">
	function confirmDelete(id, name){
		if (window.confirm('Möchtest du den Verkündiger ' + name + ' wirklich löschen')) {
			location.href = '<?php echo $this->Html->url(array('action' => 'delete')); ?>' + '/' + id;
		}
	}
</script>

<div class="publishers index">
	<legend><?php echo __('Verkündiger der Versammlung ' . $publisher['Congregation']['name']); ?></legend>

	</br>
	<p class="actions">
		<?php echo $this->Html->link('<button type="button" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Neuen Verkündiger anlegen</button>', array('action' => 'add'), array('escape' => false)); ?>
	</p>

	</br>
	<div class="table-responsive">
		<table width="100%" cellpadding="0" cellspacing="0" class="table table-striped table-hover">
			<thead>
				<tr>
					<th width="30%"><?php echo $this->Paginator->sort('email', 'Email'); ?></th>
					<th width="15%"><?php echo $this->Paginator->sort('prename', 'Vorname'); ?></th>
					<th width="15%"><?php echo $this->Paginator->sort('surname', 'Nachname'); ?></th>
					<th width="15%"><?php echo $this->Paginator->sort('phone', 'Telefon'); ?></th>
					<th width="25%" class="actions"><?php echo __('Aktionen'); ?></th>
				</tr>
			</thead>

			<tbody>

				<?php foreach ($publishers as $publisherItem): ?>
					<tr>
						<td><?php echo h($publisherItem['Publisher']['email']); ?>&nbsp;</td>
						<td><?php echo h($publisherItem['Publisher']['prename']); ?>&nbsp;</td>
						<td><?php echo h($publisherItem['Publisher']['surname']); ?>&nbsp;</td>
						<td><?php echo h($publisherItem['Publisher']['phone']); ?>&nbsp;</td>

						<td class="actions" style="white-space:nowrap;">
							<?php
								echo $this->Html->link('<button type="button" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-pencil"></span></button>', array('action' => 'edit', $publisherItem['Publisher']['id']), array('escape' => false, 'title' => 'Bearbeiten'));
								echo '<button type="button" title="Löschen" class="btn btn-default btn-xs" onclick="confirmDelete(' . $publisherItem['Publisher']['id'] . ', \'' . $publisherItem['Publisher']['surname'] . ' ' . $publisherItem['Publisher']['prename'] . '\')"><span class="glyphicon glyphicon-remove"></span></button>';
								if ($publisherItem['Publisher']['email'] && $publisherItem['Publisher']['email'] != "") {
									echo $this->Html->link('<button type="button" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-message_out"></span></button>', array('action' => 'sendAccount', $publisherItem['Publisher']['id']), array('escape' => false, 'title' => 'Zugangsdaten versenden'));
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