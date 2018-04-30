<div class="dataprotection form">
	
	<div class="col-md-6 col-xs-12">
		<legend><?php echo __('Übersicht'); ?></legend>
		<div class="panel panel-primary" style="padding:20px;">
			In die Datenschutzvereinbarung haben <?= $dataprotectionCount ?> von <?= $publisherCount ?> Verkündigern eingewilligt.<br/><br/>
			Folgende Verkündiger haben keinen Login und du hast noch keine Datenschutzvereinbarung von ihnen an mich geschickt:<br/><br/>
			<?php 
				foreach ($publishersNoDataprotectionWithoutAccount as $publisher) {
					echo $publisher['Publisher']['prename'] . " " . $publisher['Publisher']['surname'] . "<br/>";
				}
			?>
		</div>
	</div>
	
	<div class="col-md-6 col-xs-12">
		<legend><?php echo __('Formular'); ?></legend>
		<div class="panel panel-primary" style="padding:20px;">
			<p>Für alle Verkündiger ohne Login, bitte folgendes Formular herunterladen, unterschreiben lassen und bis zum 31.06.2018 an folgende Adresse schicken:</p>
			<p><b>Felix Bornmann</b><br/>
			Kasernenstr. 38<br/>
			42651 Solingen</p>
			<br/>
			<p>Nach Eingang werden die Verkündiger, die unterschrieben haben dann markiert mit "Datenschutzvereinbarung akzeptiert". Alle anderen werden nach dem 31.06.2018 gelöscht.</p>
			<a class="btn btn-primary btn-lg btn-block" href="/files/dataprotection.pdf" role="button">Formular Download</a>
		</div>
	</div>
		
</div>