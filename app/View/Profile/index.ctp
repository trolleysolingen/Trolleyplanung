<div class="col-md-6">
	<legend>Profil bearbeiten</legend>
	<?php echo $this->Form->create('Publisher', array('class' => 'form-horizontal')); 
	echo $this->Form->input('id');?>
	<div class="form-group">
		<label for="name" class="col-sm-4 control-label">Name:</label>
		<div class="col-sm-8">
			<?php
			echo $this->Form->input('name', array('label'=>false, 'class' => 'form-control', 'id' => 'name', 'value' => $publisher['Publisher']['prename'] . ' ' . $publisher['Publisher']['surname'], 'disabled' => 'disabled'));
			?>
		</div>
	</div>
	
	<div class="form-group">
		<label for="cong" class="col-sm-4 control-label">Versammlung:</label>
		<div class="col-sm-8">
			<?php
			echo $this->Form->input('cong', array('label'=>false, 'class' => 'form-control', 'id' => 'phone', 'value' => $publisher['Congregation']['name'], 'disabled' => 'disabled'));
			?>
		</div>
	</div>
	
	<div class="form-group">
		<label for="email" class="col-sm-4 control-label">Email-Adresse:</label>
		<div class="col-sm-8">
			<?php
			echo $this->Form->input('email', array('label'=>false, 'class' => 'form-control', 'id' => 'email'));
			?>
		</div>
	</div>
	
	<div class="form-group">
		<label for="phone" class="col-sm-4 control-label">Handynummer:</label>
		<div class="col-sm-8">
			<?php
			echo $this->Form->input('phone', array('label'=>false, 'class' => 'form-control', 'id' => 'phone'));
			?>
		</div>
	</div>
</div>

<div class="col-md-6">
	<legend>Einstellungen</legend>
		<?php if($publisher['Publisher']['send_mail_when_partner'] == null) { ?>
			<div class="panel panel-danger">
				<div class="panel-heading"><h3 class="panel-title">Bitte anpassen:</h3></div>
				<div class="panel-body">
					Möchtest du zukünftig Emails empfangen, wenn ein Verkündiger dich als Partner einträgt?<br/>
					<br/>
					<?php
						echo $this->Html->link('<button type="button" class="btn btn-success">Ja</button>', array('action' => 'setNewSetting', 1, 'send_mail_when_partner'), array('escape' => false, 'style' => 'text-decoration: none;'));
						echo $this->Html->link('<button type="button" class="btn btn-danger" style="margin-left:15px;">Nein</button>', array('action' => 'setNewSetting', 0, 'send_mail_when_partner'), array('escape' => false, 'style' => 'text-decoration: none;'));
					?>
				</div>
			</div>
		<?php } else { ?>
			<div class="checkbox">
				<label>
					<div class="col-xs-2 col-sm-1">
					<?php
						echo $this->Form->input('send_mail_when_partner', array('type' => 'checkbox', 'label'=>false, 'id' => 'send_mail_when_partner', 'style' => 'margin-left:0px;'));
					?> 
					</div>
					<div class="col-xs-10 col-sm-11">
						Ich möchte eine Email geschickt bekommen, wenn ich als Partner eingetragen werde
					</div>
				</label>
			</div>
		<?php }
		if($publisher['Publisher']['send_mail_for_reservation'] == null) { ?>
			<div class="panel panel-danger">
				<div class="panel-heading"><h3 class="panel-title">Bitte anpassen:</h3></div>
				<div class="panel-body">
					Möchtest du zukünftig Emails empfangen, wenn sich in deiner Schicht eine Änderung ergibt? (Jemand löscht sich oder trägt sich dazu ein)<br/>
					<br/>
					<?php
						echo $this->Html->link('<button type="button" class="btn btn-success">Ja</button>', array('action' => 'setNewSetting', 1, 'send_mail_for_reservation'), array('escape' => false, 'style' => 'text-decoration: none;'));
						echo $this->Html->link('<button type="button" class="btn btn-danger" style="margin-left:15px;">Nein</button>', array('action' => 'setNewSetting', 0, 'send_mail_for_reservation'), array('escape' => false, 'style' => 'text-decoration: none;'));
					?>
				</div>
			</div>
		<?php } else { ?>
			<div class="checkbox">
				<label>
					<div class="col-xs-2 col-sm-1">
					<?php
						echo $this->Form->input('send_mail_for_reservation', array('type' => 'checkbox', 'label'=>false, 'id' => 'send_mail_for_reservation', 'style' => 'margin-left:0px;'));
					?> 
					</div>
					<div class="col-xs-10 col-sm-11">
						Ich möchte eine Email geschickt bekommen, wenn sich etwas an meiner Schicht ändert
					</div>
				</label>
			</div>
		<?php } ?>
</div>

<div class="col-xs-12">
<br/>
<br/>
<?php echo $this->Form->end(array('label' => 'Alles abspeichern', 'class' => 'btn btn-primary')); ?>
</div>