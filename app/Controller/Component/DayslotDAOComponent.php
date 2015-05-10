<?php

App::uses('Component', 'Controller');

class DayslotDAOComponent extends Component {

    public function getDayslot($congregationId, $routeId) {
        $model = ClassRegistry::init('Dayslot');

        $result= $model->find('first',
            array(
                'conditions' => array(
                    'Dayslot.congregation_id' => $congregationId,
                    'Dayslot.route_id' => $routeId
                ),
                'recursive' => 0));

        return $result;
    }
}
