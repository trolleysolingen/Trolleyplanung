	<?php 
		$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		if (strpos($actual_link,'trolleydemo') === false) {
			$disabled = "";
		} else {
			$disabled = "disabled";
		}
	?>
	
	<p class="actions">
		<?php echo $this->Html->link('<button type="button" class="btn btn-success ' . $disabled . '"><span class="glyphicon glyphicon-plus" style="margin-top: -6px;"></span> Neues Todo erfassen</button>', array('action' => 'add'), array('escape' => false)); ?>
	</p>
	
	<br/>
	
	<?php 
	
	for($i=0; $i<4; $i++) {
		
		if($i==0) {
			$tablerows = array();
			$tablerows[] = $openBugs;
			$title = "Offene Fehler";
		} else if($i==1) {
			$tablerows = array();
			$tablerows[] = $openFeatures;
			$title = "Offene Funktionen";
		} else if($i==2) {
			$tablerows = array();
			$tablerows[] = $closedBugs;
			$title = "Behobene Fehler";
		} else if($i==3) {
			$tablerows = array();
			$tablerows[] = $finishedFeatures;
			$title = "Fertiggestellte Funktionen";
		}
		
		if(!empty($tablerows[0])){
		
			if($i<2) {
				echo "<legend>" . $title . "</legend>";
			} else { ?>
				<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<?php echo "<a data-toggle='collapse' href='#collapse_". $i . "'>"; ?>
						<span style="font-size: 1.5em; margin-top: -5px;" class="glyphicon glyphicon-expand"></span>
						<?php echo $title; ?>
						</a>
					</h4>
				</div>
			<?php  
				echo "<div id='collapse_" . $i . "' class='panel-collapse collapse'>";
				echo "<div class='panel-body'>";
			} ?>

			<div class="table-responsive">
				<table width="100%" cellpadding="0" cellspacing="0" class="table table-striped table-hover">
					<thead>
					<tr>
						<th><?php echo $this->Paginator->sort('id', 'ID'); ?></th>
						<th>Kurzbeschreibung</th>
						<th>Reporter</th>
						<th>Bearbeiter</th>
						<th><?php echo $this->Paginator->sort('creationdate', 'Erstellungsdatum'); ?></th>
						<th><?php echo $this->Paginator->sort('startdate', 'Startdatum'); ?></th>
						<th><?php echo $this->Paginator->sort('finishdate', 'Enddatum'); ?></th>
						<th class="actions"><?php echo __('Aktionen'); ?></th>
					</tr>
					</thead>

					<tbody>

					<?php foreach ($tablerows[0] as $todo): 
						$rowclass = "";
						if($i<2) {
							if($todo['Reporter']['id'] == $publisher['Publisher']['id']) {
								$rowclass = "warning";
							}
							if($todo['Todo']['startdate'] != "") {
								$rowclass = "success";
							}
							if($todo['Worker']['id'] != "") {
								if(($todo['Worker']['id'] != $publisher['Publisher']['id']) && $publisher['Role']['id'] == 2) {
									$rowclass = "danger";
								}
								if(($todo['Worker']['id'] == $publisher['Publisher']['id']) && $publisher['Role']['id'] == 2) {
									$rowclass = "info";
								}
							}
						}
					?>
						<tr class="<?php echo $rowclass;?>">
							<td><?php echo h($todo['Todo']['id']); ?>&nbsp;</td>
							<td><?php echo h($todo['Todo']['shortdesc']); ?>&nbsp;</td>
							<td><?php echo h($todo['Reporter']['prename']) . " " . h($todo['Reporter']['surname']); ?>&nbsp;</td>
							<td><?php echo h($todo['Worker']['prename']) . " " . h($todo['Worker']['surname']); ?>&nbsp;</td>
							<td><?php echo date("d.m.Y", strtotime($todo['Todo']['creationdate'])); ?>&nbsp;</td>
							<td><?php echo ($todo['Todo']['startdate'] != "" ? date("d.m.Y", strtotime($todo['Todo']['startdate'])) : ""); ?>&nbsp;</td>
							<td><?php echo ($todo['Todo']['finishdate'] != "" ? date("d.m.Y", strtotime($todo['Todo']['finishdate'])) : ""); ?>&nbsp;</td>
							<td class="actions">
							<?php
								echo $this->Html->link('<button type="button" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-display"></span></button>', array('action' => 'view', $todo['Todo']['id']), array('escape' => false, 'title' => 'Anzeigen'));
								if(($publisher['Publisher']['id'] == $todo['Reporter']['id'] || $publisher['Role']['name'] == 'admin') && $todo['Todo']['finishdate'] == "") {
									echo $this->Html->link('<button type="button" style="margin-left:5px;" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-pencil"></span></button>', array('action' => 'edit', $todo['Todo']['id']), array('escape' => false, 'title' => 'Bearbeiten'));
								}
								if($publisher['Role']['name'] == 'admin') {
									if($todo['Todo']['startdate'] == "") {
										echo $this->Html->link('<button type="button" style="margin-left:5px;" class="btn btn-success btn-xs"><span class="glyphicon glyphicon-play" style="margin-top: -3px;"></span></button>', array('action' => 'startWork', $todo['Todo']['id']), array('escape' => false, 'title' => 'Start'));
									}
									if($todo['Todo']['startdate'] != "" && $todo['Todo']['finishdate'] == "" && $publisher['Publisher']['id'] == $todo['Worker']['id']) { ?>
										<div class="btn-group">
										  <button type="button" class="btn btn-xs btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
											<span class="glyphicon glyphicon-ok" ></span> <span class="caret"></span>
										  </button>
										  <ul class="dropdown-menu" role="menu">
											<li><?php
												echo $this->Html->link('Mit standard Mail', array('action' => 'endWork', $todo['Todo']['id'], 1), array('escape' => false));
											?></li>
											<li><?php
												echo $this->Html->link('Mit eigener Mail', array('action' => 'endWork', $todo['Todo']['id'], 0), array('escape' => false));
											?></li>
										  </ul>
										</div>
									<?php }
								}
							?>
							</td>
						</tr>
					<?php endforeach; ?>

					</tbody>


				</table>
			</div>
		<?php
			if($i>1){
				echo "</div>";
				echo "</div>";
				echo "</div>";
			}
		}
	}
	?>