<?php

App::uses('Component', 'Controller');

class TimeslotDAOComponent extends Component {

    public function getAll($congregationId, $routeId) {
        $model = ClassRegistry::init('Timeslot');

        $result= $model->find('all',
            array(
                'conditions' => array(
                    'Timeslot.congregation_id' => $congregationId,
                    'Timeslot.route_id' => $routeId
                ),
                'order' => array('Timeslot.start'),
                'recursive' => 0));

        return $result;
    }
}
