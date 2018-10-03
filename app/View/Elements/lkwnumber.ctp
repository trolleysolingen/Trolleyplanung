<?php 
	if ($publisher['Congregation']['typ'] == 'FFD' && $publisher['Congregation']['show_lkw_numbers']) {
?>
<div class="panel panel-primary">
    <div class="panel-heading">
		<h4 class="panel-title">
			<a data-toggle="collapse" href="#collapse_lkw">
				<span style="font-size: 1.5em; margin-top: -5px;" class="glyphicon glyphicon-expand"></span>
				<span lang="de-at">Kennzeichen</span>
			</a>
		</h4>
	</div>
	<div id="collapse_lkw" class="panel-collapse collapse">
		<div class="panel-body">
			<p>Folgende Kennzeichen wurden auf dieser Route vor kurzem bearbeitet:</p>
			<?php 
				if (!empty($lkwnumbers)) {
			?>
				<ul>
					<?php 
						foreach ($lkwnumbers as $lkwnumber) { 
							echo "<li>" . $lkwnumber['Lkwnumber']['licenseplatenumber'] . "</li>";
						}
					?>							
				</ul>
			<?php 
				} else {
					echo "<p>---</p>";
				}
			?>
			
			<button onclick="openLkwnumberModal(<?php echo $routeId; ?>);" type="button" class="btn btn-primary">Weiteres Kennzeichen erfassen</button>
			
		</div>
	</div>
</div>
		
<?php
	}
?>