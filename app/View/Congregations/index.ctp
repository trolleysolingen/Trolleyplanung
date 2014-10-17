<script type="text/javascript">
	function confirmDelete(id, name){
		if (window.confirm('Möchtest du die Versammlung ' + name + ' wirklich löschen')) {
			location.href = '<?php echo $this->Html->url(array('action' => 'delete')); ?>' + '/' + id;
		}
	}
</script>

<div class="congregations index">
	<h2><?php echo __('Versammlungsverwaltung'); ?></h2>

	<p class="actions">
		<?php echo $this->Html->link('<button type="button" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Neue Versammlung anlegen</button>', array('action' => 'add'), array('escape' => false)); ?>
	</p>


	<div class="table-responsive">
		<table width="100%" cellpadding="0" cellspacing="0" class="table table-striped table-hover">
			<thead>
			<tr>
				<th width="50%"><?php echo $this->Paginator->sort('name', 'Name'); ?></th>
				<th width="50%" class="actions"><?php echo __('Aktionen'); ?></th>
			</tr>
			</thead>

			<tbody>

			<?php foreach ($congregations as $congregation): ?>
				<tr>
					<td><?php echo h($congregation['Congregation']['name']); ?>&nbsp;</td>
					<td class="actions">
						<?php
						echo $this->Html->link('<button type="button" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-pencil"></span></button>', array('action' => 'edit', $congregation['Congregation']['id']), array('escape' => false, 'title' => 'Bearbeiten'));
						echo '<button type="button" title="Löschen" class="btn btn-default btn-xs" onclick="confirmDelete(' . $congregation['Congregation']['id'] . ', \'' . $congregation['Congregation']['name'] . '\')"><span class="glyphicon glyphicon-remove"></span></button>';
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