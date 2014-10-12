<script>
	var publisherList = [
		<?php
			foreach ($publisherList as $publisherItem) {
				echo "{id:" . $publisherItem['Publisher']['id']. ", name: '" . $publisherItem['Publisher']['prename'] . " " . $publisherItem['Publisher']['surname'] . "'},";
			}
		?>
		];
	var displayTime = '<?php $now = new DateTime('now'); echo $now->format('Y-m-d H:i:s'); ?>';
</script>

<div class="visible-xs-block">
BILDSCHIRM DEBUG:
Größe: XS
</div>

<div class="visible-sm-block">
BILDSCHIRM DEBUG:
Größe: SM
</div>

<div class="visible-md-block">
BILDSCHIRM DEBUG:
Größe: MD
</div>

<div class="visible-lg-block">
BILDSCHIRM DEBUG:
Größe: LG
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
