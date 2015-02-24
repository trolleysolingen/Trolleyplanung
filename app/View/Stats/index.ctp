<legend>Monats Statistik</legend>

<?php
	//Include Highcharts
	echo $this->Html->script('bar-stat', array('inline'=>false));
?>

<script src="js/highcharts/highcharts.js"></script>
<script src="js/highcharts/modules/data.js"></script>
<script src="js/highcharts/modules/drilldown.js"></script>

<div class="col-xs-12 panel panel-default">
	<br/>
	<?php 
	if(!empty($monthArray)) {
		echo $this->Form->create(null, array('url' => array('controller' => 'stats'), 'class' => 'form-inline')); ?>
		  <div class="form-group">
			<label class="sr-only">Monat:</label>
			<select name="data[Stat][month]" class="form-control" id="month">
				<?php
				foreach($monthArray as $key => $value):
				echo '<option value="'.$key.'">'.$value.'</option>'; //close your tags!!
				endforeach;
				?>
			</select>
		  </div>
		<?php echo $this->Form->end(array('div' => false, 'label' => 'Generieren', 'class' => 'btn btn-primary')); 
	}	
	?>
	<br/>
</div>

<div class="col-xs-12 col-md-6">
	<div id="container" style="height: 400px; margin: 0 auto"></div>
</div>

<div class="col-xs-12 col-md-6 panel panel-default">
	<br/>
	<table class="table table-hover">
		<tr>
			<td><span class="glyphicon glyphicon-clock" style="margin-top: -4px;"></span> Berichtete Zeit:</td> 
			<td><?php echo $monthHours . " Stunden & " . $monthMinutes . " Minuten"; ?></td>
		</tr>
		<tr>
			<td><span class="glyphicon glyphicon-edit" style="margin-top: -8px;"></span> Reservierte Schichten:</td>
			<td><?php echo count($allReservations); ?> </td>
		</tr>
		<tr>
			<td><span class="glyphicon glyphicon-check" style="margin-top: -8px;"></span> Berichtete Schichten:</td>
			<td><?php echo count($givenReportList); ?></td>
		</tr>
		<tr>
			<td><span class="glyphicon glyphicon-unchecked" style="margin-top: -8px;"></span> Unberichtete Schichten:</td>
			<td><?php echo count($missingReportList); ?></td>
		</tr>
		<tr>
			<td><span class="glyphicon glyphicon-remove_2" style="margin-top: -4px;"></span> Nicht durchgeführte Schichten:</td>
			<td><?php echo count($declinedReportList); ?></td>
		</tr>		
	</table>
</div>

<pre id="tsv" style="display:none">
Publkation Monat	Abgaben
<?php
	foreach($report as $reports) {
		echo $reports . "\n";
	}
?></pre>