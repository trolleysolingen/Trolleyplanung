<?php

App::uses('Component', 'Controller');

class TimeslotDAOComponent extends Component {

    public function getAll() {
        $model = ClassRegistry::init('Timeslot');

        $result= $model->find('all', array('order' => array('Timeslot.id'), 'recursive' => 0));

        return $result;
    }
}
