<div class="start index">
    <?php
        echo $this->Form->create(null, array(
            'url' => array('controller' => 'VS-' . $congregation["Congregation"]["path"])
        ));
        echo $this->Form->input('email');
        echo $this->Form->end(__('Anmelden'));
    ?>
    <br/>
    Herzlich willkommen bei der Trolleyplanung von <?php echo $congregation["Congregation"]["name"] ?>



</div>
