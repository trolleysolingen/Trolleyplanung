<div class="congregations form">
<?php echo $this->Form->create('Congregation'); ?>
	<fieldset>
		<legend><?php echo __('Versammlung bearbeiten'); ?></legend>
		<?php
			echo $this->Form->input('id');
		?>
		<div class="form-group">
			<label for="start" class="col-sm-2 control-label">Name:</label>
			<?php
			echo $this->Form->input('name', array('label'=>false, 'class' => 'form-control', 'placeholder' => 'Name', 'style' => 'width:250px', 'id' => 'name'));
			?>
		</div>
	</fieldset>

	<?php echo $this->Form->end(array('label' => 'Speichern', 'class' => 'btn btn-primary')); ?>
</div>