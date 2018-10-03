<?php 
	if ($publisher['Congregation']['typ'] == 'Hafen') {
?>
<div class="panel panel-primary">
    <div class="panel-heading">
		<h4 class="panel-title">
			<a data-toggle="collapse" href="#collapse_ship">
				<span style="font-size: 1.5em; margin-top: -5px;" class="glyphicon glyphicon-expand"></span>
				<span lang="de-at">Schiffsnamen</span>
			</a>
		</h4>
	</div>
	<div id="collapse_ship" class="panel-collapse collapse in">
		<div class="panel-body">
			<p>Folgende Schiffe wurden auf dieser Route vor kurzem bearbeitet:</p>
			<?php 
				if (!empty($ships)) {
			?>
				<ul>
					<?php 
						foreach ($ships as $ship) { 
							echo "<li>" . $ship['Ship']['name'] . " <em style='color:grey'>(" . $ship["Ship"]["created"] . ";" . $ship['Ship']['publisher'] . ")</em></li>";
						}
					?>							
				</ul>
			<?php 
				} else {
					echo "<p>---</p>";
				}
			?>
			
			<button onclick="openShipModal(<?php echo $routeId; ?>);" type="button" class="btn btn-primary">Weiteres Schiff erfassen</button>
			
		</div>
	</div>
</div>
		
<?php
	}
?>