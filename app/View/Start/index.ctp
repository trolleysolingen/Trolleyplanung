<div class="visible-xs-block">
<?php
	echo $this->Form->create(null, array('url' => array('controller' => 'VS-' . $congregation["Congregation"]["path"])
	)); ?>
	<div class="form-group">
		<?php
			echo $this->Form->input('email', array('div' => false, 'label'=>false, 'class' => 'form-control', 'placeholder' => 'Email', 'id' => 'email'));
		?>
	</div>
	<?php echo $this->Form->end(array('class' => 'col-md-12', 'div' => false, 'label' => 'Anmelden', 'class' => 'btn btn-primary')); 
?>
</div>