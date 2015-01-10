<script>
	var publisherList = [
		<?php
			foreach ($publisherList as $publisherItem) {
				echo "'" . $publisherItem['Publisher']['prename'] . " " . $publisherItem['Publisher']['surname'] . "', ";
			}
		?>
		];
</script>

<legend>Schichten</legend>

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
			Um dich in eine Schicht einzutragen, drücke bitte auf <a href="javascript:void(0)"><span style="margin-left: 10px;" class="glyphicon glyphicon-user_add"></span></a></br>
			Zusätzlich kannst du noch einen Partner zu deiner Schicht eintragen.</br>
			<br/>
			Um an Kontaktinformationen der Verkündiger zu kommen, drücke auf das <a href="javascript:void(0)"><span style="margin-left: 5px;" class="glyphicon glyphicon-iphone"></span></a> neben dem Namen, um dich z.B. mit der Schicht vor und nach dir oder mit deinem Partner absprechen zu können.</br>
			<br/>
			Deine Schicht kannst durch drücken auf <a href="javascript:void(0)"><span style="margin-left: 5px;" class="glyphicon glyphicon-remove"></span></a> wieder löschen. Du hast dann die Option nur dich oder auch deinen Partner in der Schicht mitzulöschen. Bitte tu dies nur, wenn das auch mit deinem Partner abgesprochen ist.<br/>
			<br/>
			Bei Fragen, nutze bitte das <?php echo $this->Html->link('Kontaktformular', array('controller' => 'contact', 'action' => 'index')); ?>
		</div>
	</div>
</div>
			
<?php
	echo $this->element('week_iteration', array(
		'displaySizes' => array('lg')
	));

	echo $this->element('week_iteration', array(
		'displaySizes' => array('sm', 'md')
	));

	echo $this->element('week_iteration', array(
		'displaySizes' => array('xs')
	));
?>

<!-- Guest Modal -->
<div class="modal fade" id="guestModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Partner eintragen</h4>
      </div>
      <div class="modal-body" id="guestModalDiv">
        
      </div>
      <div class="modal-footer" id="guestModalBody">
		<div class="btn-group">
			<button type="button" class="btn btn-default" data-dismiss="modal">Schließen</button>
			<button type="button" class="btn btn-primary">Save changes</button>
		</div>
      </div>
    </div>
  </div>
</div>


<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Schicht löschen</h4>
      </div>
      <div class="modal-body" id="deleteModalDiv">
		 Möchtest du wirklich diese Schicht löschen?
		 <div id="hiddenParams">
		 
		 </div>
         <div class="checkbox" id="partnerCheckbox">
			<label>
				<input id="deletePartner" type="checkbox"> Meinen Parter ebenfalls aus der Schicht löschen 
			</label>
		 </div>
      </div>
      <div class="modal-footer" id="deleteModalBody">
		<div class="btn-group">
			<button type="button" class="btn btn-default" data-dismiss="modal">Schließen</button>
			<a href="javascript:void(0)" class="btn btn-danger" onclick="deletePublisher();">Löschen</a>
		</div>
      </div>
    </div>
  </div>
</div>
