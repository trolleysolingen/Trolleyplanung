<?php
	$rowclass = "default";
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
	if($todo['Todo']['finishdate'] != "") {
		$rowclass = "default";
	}
?>

<div class="col-sm-1 col-md-2"></div>
<div class="col-sm-9 col-md-8">
	<legend>Todo: #<?php echo h($todo['Todo']['id']); ?></legend>
	<div class="publishers view">
		<div class="panel panel-<?php echo $rowclass ?>">
		  <div class="panel-heading">
			<h3 class="panel-title"><?php echo h($todo['Todo']['shortdesc']); ?></h3>
		  </div>
		  <div class="panel-body">
			<b>Gemeldet von:</b> <?php echo h($todo['Reporter']['prename']) . " " . h($todo['Reporter']['surname']); ?> <br/>
			<b>Gemeldet am:</b> <?php echo date("d.m.Y", strtotime($todo['Todo']['creationdate'])); ?> <br/>
			<br/>
			<b>Bearbeiter:</b> <?php echo h($todo['Worker']['prename']) . " " . h($todo['Worker']['surname']); ?> <br/>
			<b>Start:</b> <?php echo ($todo['Todo']['startdate'] != "" ? date("d.m.Y", strtotime($todo['Todo']['startdate'])) : ""); ?> <br/>
			<b>Ende:</b> <?php echo ($todo['Todo']['finishdate'] != "" ? date("d.m.Y", strtotime($todo['Todo']['finishdate'])) : ""); ?> <br/>
			<br/>
			<b>Beschreibung:</b><br/>
			<?php echo nl2br(h($todo['Todo']['description'])); ?>
		  </div>
		</div>
		<?php
			if($publisher['Role']['name'] == 'admin') {
				if($todo['Todo']['startdate'] == "") {
					echo $this->Html->link('<button type="button" style="margin-left:5px;" class="btn btn-success"><span class="glyphicon glyphicon-play" style="margin-top: -3px;"></span> Bearbeitung starten</button>', array('action' => 'startWork', $todo['Todo']['id']), array('escape' => false));
				}
				if($todo['Todo']['startdate'] != "" && $todo['Todo']['finishdate'] == "" && $publisher['Publisher']['id'] == $todo['Worker']['id']) {
					echo $this->Html->link('<button type="button" style="margin-left:5px;" class="btn btn-danger"><span class="glyphicon glyphicon-stop" style="margin-top: -3px;"></span> Mit standard Mail</button>', array('action' => 'endWork', $todo['Todo']['id'], 1), array('escape' => false, 'title' => 'Beenden'));
					echo $this->Html->link('<button type="button" style="margin-left:5px;" class="btn btn-danger"><span class="glyphicon glyphicon-stop" style="margin-top: -3px;"></span> Mit eigener Mail</button>', array('action' => 'endWork', $todo['Todo']['id'], 0), array('escape' => false, 'title' => 'Beenden'));
				}
			}
		?>
	</div>
</div>