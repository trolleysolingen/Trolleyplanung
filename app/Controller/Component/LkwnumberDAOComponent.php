<?php

App::uses('Component', 'Controller');

class LkwnumberDAOComponent extends Component {

    public function getLkwnumbers($routeId) {
        $model = ClassRegistry::init('Lkwnumber');

        $result= $model->find('all',
            array(
                'conditions' => array(
                    'Lkwnumber.route_id' => $routeId,
                	'Lkwnumber.created >' => date('Y-m-d', strtotime("-2 days"))
                ),
                'order' => array('Lkwnumber.created'),
                'recursive' => 0));

        return $result;
    }
    
    public function deleteOldLkwnumbers() {
    	$model = ClassRegistry::init('Lkwnumber');
    	
    	$model->deleteAll(array('Lkwnumber.created < ' => date('Y-m-d', strtotime("-2 days"))), false);    	
    }
}
