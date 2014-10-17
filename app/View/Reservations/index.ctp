<script>
	var publisherList = [
		<?php
			foreach ($publisherList as $publisherItem) {
				echo "{id:" . $publisherItem['Publisher']['id']. ", name: '" . $publisherItem['Publisher']['prename'] . " " . $publisherItem['Publisher']['surname'] . "'},";
			}
		?>
		];
</script>

<legend>Schichten</legend>

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
