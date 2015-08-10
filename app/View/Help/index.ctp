<div style="margin-top: -40px;">
	<legend><h1>Hilfe für Verkündiger</h1></legend>
	
	Bitte lies dir aufmerksam alle Informationen durch. Falls du danach noch Fragen hast, nutze bitte das <?php echo $this->Html->link('Kontaktformular', array('controller' => 'contact', 'action' => 'index')); ?>.<br/>
	<br/>
	
	<div class="panel panel-success">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a data-toggle="collapse" href="#collapse_help">
					<span style="font-size: 1.5em; margin-top: -5px;" class="glyphicon glyphicon-expand"></span>
					Hilfe zur Schichteintragung
				</a>
			</h4>
		</div>
		<div id="collapse_help" class="panel-collapse collapse">
			<div class="panel-body">
				Um dich in eine Schicht einzutragen, drücke bitte auf <a href="javascript:void(0)"><span
						style="margin-left: 10px;" class="glyphicon glyphicon-user_add"></span></a> auf der 
						<?php echo $this->Html->link('Schichten', array('controller' => 'reservations', 'action' => 'index')); ?>-Seite.</br></br>
				Zusätzlich kannst du noch einen Partner zu deiner Schicht eintragen. Es öffnet sich ein Fenster in dem du den Namen eintippen kannst.
				Sobald du anfängst zu schreiben, öffnet sich eine Liste mit Vorschlägen von Verkündiger, die für den Trolleydienst zugelassen sind.</br>
				<br/>
				<?php if($publisher['Congregation']['guests_not_allowed']){ ?>
				Deine Versammlung hat festgelegt, dass nur die vorgegebenen Verkündiger ausgewählt werden können und keine Gäste eingetragen werden können.
				<?php } else { ?>
				Deine Versammlung hat festgelegt, dass du auch Gäste, die nicht in der vorgegebenen Liste stehen eintragen kannst.
				Wenn du das machst, erhalten die Admins deiner Versammlung eine Mail wen du eingetragen hast.
				<?php } ?>
				</br>
				</br>
				Um an Kontaktinformationen der Verkündiger zu kommen, drücke auf das <a
					href="javascript:void(0)"><span style="margin-left: 5px;"
													class="glyphicon glyphicon-iphone"></span></a> neben dem Namen,
				um dich z.B. mit der Schicht vor und nach dir oder mit deinem Partner absprechen zu können.</br>
				<br/>
				Deine Schicht kannst durch drücken auf <a href="javascript:void(0)"><span style="margin-left: 5px;"
																						  class="glyphicon glyphicon-remove"></span></a>
				wieder löschen. Du hast dann die Option nur dich oder auch deinen Partner in der Schicht
				mitzulöschen. Bitte tu dies nur, wenn das auch mit deinem Partner abgesprochen ist.<br/>
				<br/>
				
			</div>
		</div>
	</div>
</div>