<?php

App::uses('Component', 'Controller');

class TimeslotDAOComponent extends Component {

    public function getAll($publisher) {
        $model = ClassRegistry::init('Timeslot');

        $result= $model->find('all',
            array(
                'conditions' => array('Timeslot.congregation_id' => $publisher['Congregation']['id']),
                'order' => array('Timeslot.id'),
                'recursive' => 0));

        return $result;
    }
}
