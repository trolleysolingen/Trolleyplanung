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
		<form role="form">
		  <div class="form-group">
			<label for="exampleInputEmail1">Email  </label>
			<input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
		  </div>
		  <div class="form-group">
			<label for="exampleInputPassword1">Password</label>
			<input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
		  </div>
		  
		  <button type="submit" class="btn btn-default">Submit</button>
		</form>
	</div>

<?php
	print_r($contactList);
}
?>