<?php

App::uses('Component', 'Controller');

class ShipDAOComponent extends Component {

    public function getShips($routeId) {
        $model = ClassRegistry::init('Ship');

        $result= $model->find('all',
            array(
                'conditions' => array(
                    'Ship.route_id' => $routeId,
                	'Ship.created >' => date('Y-m-d', strtotime("-10 days"))
                ),
                'order' => array('Ship.created desc'),
                'recursive' => 0));

        return $result;
    }
    
    public function deleteOldShips() {
    	$model = ClassRegistry::init('Ship');
    	
    	$model->deleteAll(array('Ship.created < ' => date('Y-m-d', strtotime("-10 days"))), false);    	
    }
}
