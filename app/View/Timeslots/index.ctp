<script type="text/javascript">
	function confirmDelete(id, name){
		if (window.confirm('Möchtest du die Schicht ' + name + ' wirklich löschen')) {
			location.href = '<?php echo $this->Html->url(array('action' => 'delete')); ?>' + '/' + id;
		}
	}
</script>

<div class="timeslots index">
	<br/>
	<legend><?php echo __('Schichtzeiten der Versammlung ' . $publisher['Congregation']['name']); ?></legend>

	<p class="actions">
		<?php echo $this->Html->link('<button type="button" class="btn btn-success"><span class="glyphicon glyphicon-plus" style="margin-top: -6px;"></span> Neue Schichtzeit anlegen</button>', array('action' => 'add'), array('escape' => false)); ?>
	</p>


	<div class="table-responsive">
		<table width="100%" cellpadding="0" cellspacing="0" class="table table-striped table-hover">
			<thead>
			<tr>
				<th width="30%"><?php echo $this->Paginator->sort('start', 'Startzeit'); ?></th>
				<th width="30%"><?php echo $this->Paginator->sort('end', 'Endezeit'); ?></th>
				<th width="40%" class="actions"><?php echo __('Aktionen'); ?></th>
			</tr>
			</thead>

			<tbody>

			<?php foreach ($timeslots as $timeslot): ?>
				<tr>
					<td><?php echo h($timeslot['Timeslot']['start']); ?>&nbsp;</td>
					<td><?php echo h($timeslot['Timeslot']['end']); ?>&nbsp;</td>
					<td class="actions">
						<?php
						echo $this->Html->link('<button type="button" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-pencil"></span></button>', array('action' => 'edit', $timeslot['Timeslot']['id']), array('escape' => false, 'title' => 'Bearbeiten'));
						echo $this->Html->link('<button type="button" data-data="' . $timeslot['Timeslot']['start'] . ' - ' . $timeslot['Timeslot']['end'] . '" class="open-DeleteDialog btn btn-default btn-xs"><span class="glyphicon glyphicon-remove"></span></button>', '#', array('data-toggle'=> 'modal', 'data-target' => '#ConfirmDelete', 'data-action'=> Router::url(array('action'=>'delete',$timeslot['Timeslot']['id'])), 'escape' => false), false);
						
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
        <h4 class="modal-title" id="myModalLabel">Schichtzeit löschen</h4>
      </div>
      <div class="modal-body">
        Möchtest du die Schichtzeit <b><div id="data" name="data"></div></b> wirklich löschen?
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

<!-- Confirmation Script to get id to delete -->
<?php $this->addScript('testscript', "$('#ConfirmDelete').on('show.bs.modal', function(e) {
    $(this).find('form').attr('action', $(e.relatedTarget).data('action'));
});"); ?>