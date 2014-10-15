<div class="publishers form">
<?php echo $this->Form->create('Publisher'); ?>
	<fieldset>
		<legend><?php echo __('Verkündiger bearbeiten'); ?></legend>
		<?php
			echo $this->Form->input('id');
			echo $this->Form->input('congregation_id', array(
				'type' => 'hidden',
				'value' => $publisher['Congregation']['id']
			));
		?>
		<div class="form-group">
			<label for="email" class="col-sm-2 control-label">E-Mail-Adresse:</label>
			<?php
			echo $this->Form->input('email', array('label'=>false, 'class' => 'form-control', 'placeholder' => 'E-Mail-Adresse', 'style' => 'width:250px', 'id' => 'email'));
			?>
		</div>
		<div class="form-group">
			<label for="password" class="col-sm-2 control-label">Passwort:</label>
			<?php
			echo $this->Form->input('password', array('type'=> 'text', 'label'=>false, 'class' => 'form-control', 'placeholder' => 'Passwort', 'style' => 'width:250px', 'id' => 'password'));
			?>
		</div>
		<div class="form-group">
			<label for="prename" class="col-sm-2 control-label">Vorname:</label>
			<?php
			echo $this->Form->input('prename', array('label'=>false, 'class' => 'form-control', 'placeholder' => 'Vorname', 'style' => 'width:250px', 'id' => 'prename'));
			?>
		</div>
		<div class="form-group">
			<label for="surname" class="col-sm-2 control-label">Nachname:</label>
			<?php
				echo $this->Form->input('surname', array('label'=>false, 'class' => 'form-control', 'placeholder' => 'Nachname', 'style' => 'width:250px', 'id' => 'surname'));
			?>
		</div>

		<div class="form-group">
			<label for="role_id" class="col-sm-2 control-label">Rolle:</label>
			<?php
				echo $this->Form->input('role_id', array('label'=>false, 'class' => 'form-control', 'style' => 'width:250px', 'id' => 'role_id', 'options' => array('' => 'Bitte wähle eine Rolle aus') + $roles));
			?>
		</div>

		<div class="form-group">
			<label for="phone" class="col-sm-2 control-label">Telefon:</label>
			<?php
			echo $this->Form->input('phone', array('label'=>false, 'class' => 'form-control', 'placeholder' => 'Telefon', 'style' => 'width:250px', 'id' => 'phone'));
			?>
		</div>

	</fieldset>

	<?php echo $this->Form->end(array('label' => 'Speichern', 'class' => 'btn btn-primary')); ?>
</div>
