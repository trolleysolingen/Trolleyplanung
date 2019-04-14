<div class="col-xs-12"  style="padding-top:20px">
	<legend>Suche</legend>
	<?php 
		echo $this->Form->create('Shiplist', array('url' => array('controller' => 'shiplists', 'action' => 'index'), 'class' => 'form-inline')); 
		echo $this->Form->input('shipname', array('div' => false, 'label'=>false,  'class' => 'form-control', 'placeholder' => 'Schiffsname', 'id' => 'shipname'));
		echo $this->Form->input('imo', array('div' => false, 'label'=>false,  'class' => 'form-control', 'placeholder' => 'IMO Nummer', 'id' => 'imo'));
		echo $this->Form->end(array('div' => false, 'label' => 'Suchen', 'class' => 'btn btn-primary')); 
	?>
</div>
<div class="col-xs-12"  style="padding-top:20px">
	<?php 
	if ($publisher['Role']['name'] == 'admin' || $publisher['Role']['name'] == 'congregation admin') {
		echo $this->Html->link('Exportieren', array(
			'controller' => 'shiplists', 
			'action' => 'export',
			'ext' => 'csv'
		));
	}
	?>
</div>

<?php 
	if (!empty($shiplist)) {
?>
		<div class="col-xs-12" style="padding-top:20px">
			<legend>Schiffsliste</legend>
			<table width="100%" cellpadding="0" cellspacing="0" class="table table-striped table-hover">
				<thead>
					<tr>
						<th>Schiffsname</th>
						<th>IMO #</th>
						<th>Schiffstyp</th>
						<th>Verkündiger</th>
						<th>Letzter Besuch</th>
						<th>Nächster Besuch</th>
						<th>Reservierung</th>
					</tr>
				</thead>
		
				<tbody>
		
					<?php foreach ($shiplist as $shiplistItem): ?>
						<tr>
							<td><?= h($shiplistItem['Shiplist']['shipname']); ?> </td>
		
							<td><?= h($shiplistItem['Shiplist']['imo']); ?></td>
							
							<td><?= h($shiplistItem['Shiplist']['shiptype']); ?></td>
							
							<td><?= h($shiplistItem['Shiplist']['publishers']); ?></td>
		
							<td><?= h($shiplistItem['Shiplist']['visit']); ?></td>
							
							<td><?= h($shiplistItem['Shiplist']['returnvisit']); ?></td>
							
							<td><?= h($shiplistItem['Shiplist']['reservation']); ?></td>
		
						</tr>
					<?php endforeach; ?>
		
				</tbody>		
			</table>
		</div>
	
<?php 	
	}

?>