<div class="visible-xs-block visible-sm-block">
<legend>Anmelden</legend>
<?php
	echo $this->Form->create(null, array('url' => array('controller' => 'start')
	)); ?>
	<div class="form-group">
		<?php
			echo $this->Form->input('email', array('div' => false, 'label'=>false, 'class' => 'form-control', 'placeholder' => 'Email', 'id' => 'email'));
			echo "</br>";
			echo $this->Form->input('password', array('div' => false, 'label'=>false, 'class' => 'form-control', 'placeholder' => 'Passwort', 'id' => 'password'));
		?>
	</div>
	<div class="row">
		<?php echo $this->Form->end(array('class' => 'col-md-12', 'div' => false, 'label' => 'Anmelden', 'class' => 'btn btn-primary', 'style' => 'margin-right:15px; margin-left:15px;'));
		echo $this->Html->link('Impressum/Kontakt', array('controller' => 'contact', 'action' => 'index'), array('class' => 'btn btn-warning')); ?>
	</div>
	<br/>
	<br/>
</div>

<div id="carousel-example-generic" class="carousel slide col-md-6" data-ride="carousel">
  <!-- Indicators -->
  <ol class="carousel-indicators">
    <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
    <li data-target="#carousel-example-generic" data-slide-to="1"></li>
    <li data-target="#carousel-example-generic" data-slide-to="2"></li>
  </ol>

  <!-- Wrapper for slides -->
  <div class="carousel-inner" role="listbox">
    <div class="item active">
      <img src="/img/lap.jpg" alt="Laptop" class="img-rounded">
      <div class="carousel-caption">
        Egal ob am Laptop...
      </div>
    </div>
    <div class="item">
      <img src="/img/tab.jpg" alt="Tablet" class="img-rounded">
      <div class="carousel-caption">
        ...am Tablet...
      </div>
    </div>
    <div class="item">
      <img src="/img/phone.jpg" alt="Handy" class="img-rounded">
      <div class="carousel-caption">
        ...oder am Handy...
      </div>
    </div>
  </div>

  <!-- Controls -->
  <a class="left carousel-control img-rounded" href="#carousel-example-generic" role="button" data-slide="prev" style="margin-left:15px;">
    <span class="glyphicon glyphicon-chevron-left"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="right carousel-control img-rounded" href="#carousel-example-generic" role="button" data-slide="next" style="margin-right:15px;">
    <span class="glyphicon glyphicon-chevron-right"></span>
    <span class="sr-only">Next</span>
  </a>
</div>
<div class="hidden-lg hidden-md">
<br/>
</div>
<div class="col-md-6 panel panel-default">
	<h3>...die <?php echo $this->Session->read('verwaltungTyp'); ?>-Verwaltung ist immer mit dabei!</h2>
	<br/>
	<br/>
	<span class="glyphicon glyphicon-ok_2 check"></span> Einfache Verwaltung der <?php echo $this->Session->read('verwaltungTyp'); ?>-Schichten:<br/>
	<ul>
		<li>Für eine Schicht eintragen</li>
		<li>Einen Partner sowohl aus der Versammlung, als auch frei wählbar dazu eintragen</li>
		<li>Auf einen Blick alle Kontaktinformationen zu allen Schichten</li>
		<li>Sowohl sich selbst, als auch seinen Partner aus einer Schicht wieder löschen</li>
		<li>Bekomme Benachrichtigungen, wenn sich jemand zu deiner Schicht einträgt oder löscht</li>
	</ul>
	<br/>
	<span class="glyphicon glyphicon-ok_2 check"></span> Leichte Administration:<br/>
	<ul>
		<li>Schichtzeiten selber definieren</li>
		<li>Definiere die Tage an denen <?php echo $this->Session->read('verwaltungTyp'); ?>-Dienst durchgeführt werden soll</li>
		<li>Verkündiger eintragen und Zugangsmails verschicken</li>
		<li>Bekomme Benachrichtungen, wenn ein Verkündiger deiner Versammlung einen Partner einträgt, der nicht zu deinen eingetragenen Verkündigern gehört</li>
		<li>Schlüsselverwaltung: Gib an welche Verkündiger Zugang zum <?php echo $this->Session->read('verwaltungTyp'); ?> besitzen</li>
	</ul>
	<span class="glyphicon glyphicon-ok_2 check"></span> Ständige Weiterentwicklung der Software basierend auf Wünschen der Benutzer<br/>
	<br>
	Deine Versammlung möchte einen <b>Zugang</b>? Kein Problem!<br/>
	Schicke uns einfach eine Mail (<a href="mailto:info@trolley.jw-center.com">info@trolley.jw-center.com</a>) und wir werden uns so schnell es geht mit dir in Verbindung setzen.<br/>
	<br/>
	<div style="font-family: 'Stalemate', serif; font-size:50px;">Deine <?php echo $this->Session->read('verwaltungTyp'); ?>-Verwaltung</div><br/>
	
</div>