<?php

App::uses('Component', 'Controller');

class CongregationDAOComponent extends Component {

    public function getRoutes($congregationId) {
        $model = ClassRegistry::init('Routes');

        $result= $model->find('all', array('conditions' => array('congregation_id' => $congregationId), 'recursive' => 0));

        return $result;
    }



	public function killswitchAllCongregations() {
		$db = ConnectionManager::getDataSource('default');
		$db->rawQuery('UPDATE congregations SET killswitch=1');
	}
	
	public function removeKillswitchFromAllCongregations() {
		$db = ConnectionManager::getDataSource('default');
		$db->rawQuery('UPDATE congregations SET killswitch=0');
	}
	
	public function getKillswitchState($publisher) {
        $model = ClassRegistry::init('Congregation');
        $result = $model->find('first', array(
                'fields' => array('Congregation.killswitch'),
                'recursive' => -1,
                'conditions' => array(
                    'Congregation.id' => $publisher['Congregation']['id']
                )
            )
        );

        return $result;
    }
	
	public function getAllKillswitchStates() {
        $model = ClassRegistry::init('Congregation');
        $results = $model->find('all', array(
                'fields' => array('Congregation.killswitch'),
                'recursive' => -1
            )
        );
		
		$oneActive = false;
		
		foreach ($results as $result) {
			if($result['Congregation']['killswitch']) {
				$oneActive = true;
			}
		}

        return $oneActive;
    }
}
