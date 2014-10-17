<div class="visible-xs-block">
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
	<?php echo $this->Form->end(array('class' => 'col-md-12', 'div' => false, 'label' => 'Anmelden', 'class' => 'btn btn-primary')); 
?>
</div>