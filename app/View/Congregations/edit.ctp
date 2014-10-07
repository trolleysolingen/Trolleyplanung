<div class="congregations form">
<?php echo $this->Form->create('Congregation'); ?>
	<fieldset>
		<legend><?php echo __('Edit Congregation'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
