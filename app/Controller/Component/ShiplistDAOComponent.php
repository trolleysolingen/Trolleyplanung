<?php

App::uses('Component', 'Controller');

class ShiplistDAOComponent extends Component {

    public function searchShiplists($shipname, $imo) { 	
        $model = ClassRegistry::init('Shiplist');

        $result= $model->find('all',
            array(
                'conditions' => array(
                    'shipname LIKE' => '%'. $shipname . '%',
    				'imo LIKE' => '%'. $imo . '%'
    					
                ),
                'order' => array('Shiplist.visit desc'),
                'recursive' => -1));

        return $result;
    }
    
     public function saveShiplist($reservation, $shiplistEntry) {
     	$model = ClassRegistry::init('Shiplist');
     	
     	if (!empty($shiplistEntry['shipname'])) {
     		$shiplistEntry['route_id'] = $reservation['Reservation']['route_id'];
	     	
	     	$i = 0;
	     	$publishers = '';
	     	foreach($reservation['Publisher'] as $reservationPublisher) {
	     		if ($i != 0) {
	     			$publishers .= ' / ';
	     		}
	     		$publishers .= $reservationPublisher["prename"] . " " . $reservationPublisher["surname"];
	     		$i++;
	     	}
	     	
	     	$shiplistEntry['publishers'] = $publishers;
	     	$shiplistEntry['visit'] = $reservation['Reservation']['day'];
	     	
	     	$date = new DateTime($reservation['Reservation']['day']);
	     	if (!empty($shiplistEntry['recommendation'])) {
	     		$date->add(new DateInterval($shiplistEntry['recommendation']));
	     	}
	     	$shiplistEntry['returnvisit'] = $date->format('Y-m-d');
	     	
	     	$res = $model->save($shiplistEntry);
	     	$model->clear();
     	}
     }
}
