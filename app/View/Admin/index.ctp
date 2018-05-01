<div class="col-xs-12">
	<legend>Admin Settings</legend>
	<?php
		echo $this->Html->link('<button type="button" class="btn btn-danger" style="margin-right:15px;">Alle Verkündiger ausloggen</button>', array('action' => 'logoutAllUsers'), array('escape' => false, 'style' => 'text-decoration: none;'));
		echo "<br/><br/>";
		
		echo $this->Html->link('<button type="button" class="btn btn-danger" style="margin-right:15px;">Allen Versammlungen den Login verwehren</button>', array('action' => 'killswitchAllCongregations'), array('escape' => false, 'style' => 'text-decoration: none;'));
		echo $this->Html->link('<button type="button" class="btn btn-success" style="margin-right:15px;">Allen Versammlungen den Login gewähren</button>', array('action' => 'removeKillswitchFromAllCongregations'), array('escape' => false, 'style' => 'text-decoration: none;'));
		echo "<br/><br/>";
		
		echo $this->Form->create(null, array('url' => array('controller' => 'admin'), 'class' => 'form-inline')); ?>
		  <div class="form-group">
			<label>Versammlung:</label>
			<select name="data[Congregation][kill]" class="form-control" id="kill">
				<?php
				foreach($congregations as $congregation):
				echo '<option value="'.$congregation['Congregation']['id'].'">'.$congregation['Congregation']['name'].'</option>'; //close your tags!!
				endforeach;
				?>
			</select>
		  </div>
		<?php echo $this->Form->end(array('div' => false, 'label' => 'Login verwehren', 'class' => 'btn btn-danger')); ?>
		<br/><br/>
		<?= $allDataprotectionUserCount ?> von <?= $allUserCount ?> Verkündigern haben die Datenschutzerklärung akzeptiert.
</div>