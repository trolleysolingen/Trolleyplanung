<legend>Impressum/Kontakt</legend>

<div class="col-md-6 col-xs-12">
	<div class="row">
		<div class="text-center">
			<img src="/img/fb.jpg" class="img-circle col-xs-4" />
		</div>
		<div class="col-xs-8 vert-align">
			<blockquote>
				<b>Felix Bornmann</b></br>
				Ohligser Feld 10</br>
				42697 Solingen</br>
				Deutschland</br>
				</br>
				<span class="glyphicon glyphicon-phone_alt"></span>&nbsp;&nbsp;<a href="tel:017642057020">017642057020</a></br>
				<span class="glyphicon glyphicon-message_new"></span>&nbsp;&nbsp;<a href="mailto:info@trolley.jw-center.com">info@trolley.jw-center.com</a></br>
				</br>
				<b>Webseiten Inhaber und technischer Kontakt</b>
			</blockquote>
		</div>
	</div>
</div>

<?php if($publisher) {?>

	<div class="col-md-6 col-xs-12">
		<div class="panel panel-default">
		  <div class="panel-body">
			Für alle Fragen, die den Trolleydienst deiner Versammlung betreffen, verwende bitte die Kontakte neben/unter dem Kontaktformular.</br>
			Benutzt bitte für alle anderen <b>Fragen, Anregungen, Fehlermeldungen und Lobgesänge</b> folgendes Kontaktformular.
		  </div>
		</div>
		<?php $pubName = $publisher['Publisher']['prename'] . " " . $publisher['Publisher']['surname'];
		echo $this->Form->create(null, array('url' => array('controller' => 'contact'), 'class' => 'form-horizontal'
		)); ?>
		<div class="form-group">
			<label for="name" class="col-sm-2 control-label">Name</label>
			<div class="col-sm-10">
				<?php echo $this->Form->input('name', array('div' => false, 'label'=>false, 'class' => 'form-control', 'id' => 'name', 'value' => $pubName, 'disabled' => 'disabled')); ?>
			</div>
		</div>
		
		<div class="form-group">
			<label for="email" class="col-sm-2 control-label">Email</label>
			<div class="col-sm-10">
				<?php echo $this->Form->input('email', array('div' => false, 'label'=>false, 'class' => 'form-control', 'id' => 'email', 'value' => $publisher['Publisher']['email'], 'disabled' => 'disabled')); ?>
			</div>
		</div>
		
		<div class="form-group">
			<label for="subject" class="col-sm-2 control-label">Betreff</label>
			<div class="col-sm-10">
				<?php echo $this->Form->input('subject', array('div' => false, 'label'=>false, 'class' => 'form-control', 'id' => 'subject')); ?>
			</div>
		</div>
		
		<div class="form-group">
			<label for="message" class="col-sm-2 control-label">Nachricht</label>
			<div class="col-sm-10">
				<?php echo $this->Form->textarea('message', array('div' => false, 'label'=>false, 'class' => 'form-control', 'id' => 'message', 'rows' => '5')); ?>
			</div>
		</div>
		
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<?php echo $this->Form->end(array('div' => false, 'label' => 'Abschicken', 'class' => 'btn btn-primary')); ?>
			</div>
		</div>
	</div>

<?php
}
?>