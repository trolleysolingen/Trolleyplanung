<div class="dataprotection form">
	
	<fieldset>
		<legend><h1>Einwilligung zur Datenverarbeitung</h1></legend>
		
		<p>
		Liebe Schwester, lieber Bruder,
		</p>
		
		<p>
		Ab dem 25. Mai gilt in der EU eine neue Datenschutzverordnung.
		Deshalb benötigen wir deine Einwilligung in folgende Datenschutzerklärung:
		</p>
		
		<p>
		Hiermit erkläre ich mich bereit, dass meine folgenden personenbezogenen
		Daten durch "JW-Center" (https://jw-center.com) gespeichert und verarbeitet
		werden dürfen.
		</p>
		
		<p>
		Auf "JW-Center" sind mein Vorname, Nachname, E-Mail-Adresse und gegebenenfalls meine
		Telefonnummer gespeichert. Mein Vorname und Nachname wird dafür
		benötigt, dass andere Nutzer mich finden können und in "Schichten"
		eintragen können. Ich bin damit einverstanden, dass mein Vorname und
		Nachname und gegebenenfalls meine Telefonnummer in der
		"Schichtplanung" auftauchen und von allen Zugriffsberechtigten dieser
		"Schichtplanung" gesehen werden können.
		</p>
		
		<p>
		Ich bin mir bewusst, dass ein "Administrator" dieser "Schichtplanung" meine
		Daten in das System eingegeben und gespeichert hat. "Administratoren"
		haben vollen Zugriff auf diese Daten und können sie einsehen, verändern
		und löschen.
		</p>
		
		<p>
		Weitere allgemeine Hinweise finde ich auf https://jw-center.com unter dem
		Punkt "Datenschutzerklärung". Ich habe diese Hinweise gelesen und stimme
		ihnen zu.
		</p>
		
		<p>
		Ich habe jederzeit das Recht unentgeltlich Auskunft über Herkunft,
		Empfänger und Zweck meiner gespeicherten personenbezogenen Daten zu
		erhalten. Ich habe außerdem ein Recht, die Berichtigung, Sperrung oder
		Löschung dieser Daten zu verlangen.
		</p>
	
		<p>
		Um die Anwendung weiterhin benutzen zu können, musst du der Datenschutzerklärung zustimmen. Ansonsten wird dein Account gelöscht.
		</p>
		
	</fieldset>
	
	<?php
	    echo $this->Html->link('<button type="button" data-data="" class="open-Dialog btn btn-secondary"><span>Ich stimme nicht zu</span></button>', '#', array('data-toggle'=> 'modal', 'data-target' => '#ConfirmDelete', 'data-action'=> Router::url(array('action'=>'reject')), 'escape' => false), false);
	    	
	    echo "&nbsp;&nbsp;";
		echo $this->Html->link('<button type="button" class="btn btn-primary"><span>Ich stimme zu</span></button>', array('controller' => 'dataprotection', 'action' => 'accept'), array('escape' => false, 'title' => 'Ich stimme zu'));
	?>
	
		
</div>

<!-- Modal -->
<div class="modal fade" id="ConfirmDelete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Nicht zustimmen</h4>
      </div>
      <div class="modal-body">
        Möchtest du der Datenschutzerklärung wirklich nicht zustimmen? Dein Account wird dann gelöscht.
      </div>
      <div class="modal-footer">
		<div class="btn-group">
			<button type="button" class="btn btn-default" data-dismiss="modal">Nein</button>
			<?php
			echo $this->Form->postLink(
					'Ja',
					array('action' => 'reject'),
					array('class' => 'btn btn-danger'),
					false
				);
			?>
		</div>
      </div>
    </div>
  </div>
</div>