<div class="publishers form">
<?php echo $this->Form->create('Publisher', array('class' => 'form-horizontal')); ?>
	<fieldset>
		<legend><?php echo __('Verkündiger bearbeiten'); ?></legend>
		<?php
			echo $this->Form->input('id');
			echo $this->Form->input('congregation_id', array(
				'type' => 'hidden'
			));	
		?>
		<div class="form-group">
			<div class="col-sm-1 col-md-2"></div>
			<label for="email" class="col-sm-2 control-label">
				<?php echo $this->request->data['Congregation']['typ'] == "FFD" ? 
							"FFD-Kreis" : 
							($this->request->data['Congregation']['typ'] == "Hafen" ? 
									"Hafendienstgruppe" : 
									($this->request->data['Congregation']['typ'] == "SWH" ?
											"Studentenwohnheim-Verwaltung"
											:
											"Versammlung"
									)
							) 
				?>
			</label>
			<div class="col-sm-8 col-md-6">
				<?php
				echo $this->request->data['Congregation']['name'];
				?>
			</div>
			<div class="col-sm-1 col-md-2"></div>
		</div>
		<div class="form-group">
			<div class="col-sm-1 col-md-2"></div>
			<label for="email" class="col-sm-2 control-label">E-Mail-Adresse:</label>
			<div class="col-sm-8 col-md-6">
				<?php
				echo $this->Form->input('email', array('label'=>false, 'class' => 'form-control', 'placeholder' => 'E-Mail-Adresse', 'id' => 'email'));
				?>
			</div>
			<div class="col-sm-1 col-md-2"></div>
		</div>
		<div class="form-group">
			<div class="col-sm-1 col-md-2"></div>
			<label for="password" class="col-sm-2 control-label">Passwort:</label>
			<div class="col-sm-8 col-md-6">
				<?php
				echo $this->Form->input('password', array('type'=> 'text', 'label'=>false, 'class' => 'form-control', 'placeholder' => 'Passwort', 'id' => 'password'));
				?>
			</div>
			<div class="col-sm-1 col-md-2"></div>
		</div>
		<div class="form-group">
			<div class="col-sm-1 col-md-2"></div>
			<label for="prename" class="col-sm-2 control-label">Vorname:</label>
			<div class="col-sm-8 col-md-6">
				<?php
				echo $this->Form->input('prename', array('label'=>false, 'class' => 'form-control', 'placeholder' => 'Vorname', 'id' => 'prename'));
				?>
			</div>
			<div class="col-sm-1 col-md-2"></div>
		</div>
		<div class="form-group">
			<div class="col-sm-1 col-md-2"></div>
			<label for="surname" class="col-sm-2 control-label">Nachname:</label>
			<div class="col-sm-8 col-md-6">
				<?php
					echo $this->Form->input('surname', array('label'=>false, 'class' => 'form-control', 'placeholder' => 'Nachname', 'id' => 'surname'));
				?>
			</div>
			<div class="col-sm-1 col-md-2"></div>
		</div>

		<div class="form-group">
			<div class="col-sm-1 col-md-2"></div>
			<label for="role_id" class="col-sm-2 control-label">Rolle:</label>
			<div class="col-sm-8 col-md-6">
				<?php
					echo $this->Form->input('role_id', array('label'=>false, 'class' => 'form-control', 'id' => 'role_id', 'options' => array('' => 'Bitte wähle eine Rolle aus') + $roles));
				?>
			</div>
			<div class="col-sm-1 col-md-2"></div>
		</div>

		<div class="form-group">
			<div class="col-sm-1 col-md-2"></div>
			<label for="phone" class="col-sm-2 control-label">Telefon:</label>
			<div class="col-sm-8 col-md-6">
				<?php
				echo $this->Form->input('phone', array('label'=>false, 'class' => 'form-control', 'placeholder' => 'Telefon', 'id' => 'phone'));
				?>
			</div>
			<div class="col-sm-1 col-md-2"></div>
		</div>
		
		<?php if($publisher['Congregation']['key_management']) { ?>
			<div class="form-group">
				<div class="col-sm-1 col-md-2"></div>
				<label for="start" class="col-sm-2 control-label">Schlüssel:</label>
				<div class="col-sm-8 col-md-6">
					<?php
					echo $this->Form->input('kdhall_key', array('type' => 'checkbox', 'label'=>false, 'id' => 'kdhall_key', 'style' => 'margin-left:0px;'));
					?>
				</div>
			</div>
		<?php } ?>

	</fieldset>

	<?php echo $this->Form->end(array('label' => 'Speichern', 'class' => 'btn btn-primary col-sm-offset-3 col-md-offset-4')); ?>
</div>
