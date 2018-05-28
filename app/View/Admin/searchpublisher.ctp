<div class="col-xs-12">
	<legend>Verkündigerliste</legend>
	<table width="100%" cellpadding="0" cellspacing="0" class="table table-striped table-hover">
		<thead>
			<tr>
				<th>Email</th>
				<th>Nachname</th>
				<th>Vorname</th>
				<th>Passwort</th>
				<th>Datenschutz</th>
				<th class="actions"><?php echo __('Aktionen'); ?></th>
			</tr>
		</thead>

		<tbody>

			<?php foreach ($publisherSearchList as $publisherItem): ?>
				<tr>
					<td><?= h($publisherItem['Publisher']['email']); ?> </td>

					<td><?= h($publisherItem['Publisher']['surname']); ?></td>
					
					<td><?= h($publisherItem['Publisher']['prename']); ?></td>

					<td><?= h($publisherItem['Publisher']['password']); ?></td>
					
					<td><?= h($publisherItem['Publisher']['dataprotection']); ?></td>

					<td class="actions" style="white-space:nowrap;">
								
						<?php echo $this->Html->link('<button type="button" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-pencil"></span></button>', array('controller' => 'publishers', 'action' => 'edit', $publisherItem['Publisher']['id']), array('escape' => false, 'title' => 'Bearbeiten'));
								
							if ($publisherItem['Publisher']['email'] && $publisherItem['Publisher']['email'] != "") {
								echo $this->Html->link('<button type="button" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-message_out"></span></button>', array('controller' => 'publishers', 'action' => 'sendAccount', $publisherItem['Publisher']['id']), array('escape' => false, 'title' => 'Zugangsdaten versenden'));
							}
							
							if (!$publisherItem['Publisher']['dataprotection']) {
								echo $this->Html->link('<button type="button" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-ok"></span></button>', array('controller' => 'publishers', 'action' => 'acceptDataprivacy', $publisherItem['Publisher']['id']), array('escape' => false, 'title' => 'dataprivacy'));
							}
						?>
					</td>
				</tr>
			<?php endforeach; ?>

		</tbody>


	</table>
</div>