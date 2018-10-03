<?php 
	if ($publisher['Congregation']['typ'] == 'Hafen') {
?>

<!-- Ship Modal -->
<div class="modal fade" id="shipModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
	  <?php echo $this->Form->create(null, array('url' => array('action' => 'saveShip'), 'class' => 'form-horizontal')); ?>
	  <?php echo $this->Form->input('route_id', array('id' => 'routeId', 'type' => 'hidden')); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Schiff erfassen</h4>
      </div>
      <div class="modal-body">
		<div class="form-group">
			<div class="col-xs-12">
				<?php echo $this->Form->input('shipname', array('label'=>false, 'class' => 'form-control', 'placeholder' => 'Name', 'id' => 'name', 'maxlength' => '30')); ?>				
			</div>			
		</div>
      </div>
      <div class="modal-footer">
		<div class="btn-group">
			<button type="button" class="btn btn-default" data-dismiss="modal">Schließen</button>
			<?php echo $this->Form->end(array('div' => false, 'label' => 'Abschicken', 'class' => 'btn btn-success')); ?>
		</div>
      </div>
    </div>
  </div>
</div>

<script>
	function openShipModal(routeId) {
		$("#routeId").val(routeId);
		$('#shipModal').modal('show');
	}
</script>

<?php
	}
?>