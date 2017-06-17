<?php

App::uses('Component', 'Controller');

class ReservationDAOComponent extends Component {

    public $components = array('PublisherDAO', 'MailService');
	
	public function getByRealId($id) {
        $model = ClassRegistry::init('Reservation');

        $result= $model->find('first', array('conditions' => array('Reservation.id' => $id), 'recursive' => 1));

        return $result;
    }

    public function getReservationsInTimeRange($mondayThisWeek, $congregationId, $routeId) {
        $model = ClassRegistry::init('Reservation');

        $lastDateOnView = strtotime('monday this week +' . Configure::read('DISPLAYED_WEEKS'). ' week');

        $result= $model->find('all', array(      	
        	'fields' => array(
        			'Reservation.id',
        			'Reservation.congregation_id',
        			'Reservation.day',
        			'Reservation.timeslot_id',
        			'Reservation.modified',
        			'Reservation.route_id',
        			'Congregation.id',
        			'Congregation.guests_not_allowed',
        			'Route.id',
        			'Route.congregation_id',
        			'Route.publishers',
        			'Timeslot.id',
        			'Timeslot.congregation_id',
        			'Timeslot.start',
        			'Timeslot.end',
        			'Timeslot.route_id',
        			'Timeslot.day',        			      			
        	),
        	'contain' => array(
        			'PublisherReservation' => array(
        					'fields' => array(
        							'PublisherReservation.id',
        							'PublisherReservation.publisher_id',
        							'PublisherReservation.reservation_id',
        							'PublisherReservation.guestname',
        					),        					
        			),
        			'Publisher' => array(
        					'fields' => array(
        							'Publisher.id',
				        			'Publisher.email',
				        			'Publisher.prename',
				        			'Publisher.surname',
				        			'Publisher.congregation_id',
				        			'Publisher.role_id',
				        			'Publisher.phone',
				        			'Publisher.kdhall_key',
				        			'Publisher.log_out',
				        			'Publisher.send_mail_when_partner',
				        			'Publisher.send_mail_for_reservation',
        					),
        			)
        	),
        	
            'conditions' => array(
                'Reservation.day between STR_TO_DATE(?, \'%d.%m.%Y\') and STR_TO_DATE(?, \'%d.%m.%Y\')' =>
                    array(date("d.m.Y", $mondayThisWeek), date("d.m.Y", $lastDateOnView)),
                'Reservation.congregation_id' => $congregationId,
                'Reservation.route_id' => $routeId
            ),
            'order' => array('Reservation.day', 'Reservation.timeslot_id'),
            'recursive' => 1
            )
        );        
        
        return $result;
    }

    public function addPublisher($congregationId, $routeId, $reservationDay, $reservationTimeslot, $displayTime, $publisher) {
        $model = ClassRegistry::init('Reservation');
		$model2 = ClassRegistry::init('PublisherReservation');

        $reservation = $model->find('first', array(
                'conditions' => array(
                    'Reservation.day' => $reservationDay,
                    'Reservation.timeslot_id' => $reservationTimeslot,
                    'Reservation.congregation_id' => $congregationId,
                    'Reservation.route_id' => $routeId
                ),
            'recursive' => -1
            )
        );

        $sendMail = true;
        if ($reservation != null && $reservation['Reservation']['modified'] > $displayTime) {
            // Reservation has been modified -> don't save
            $reservation['error'] = 'Der Termin wurde zwischenzeitlich verändert. Bitte überprüfe deine Buchung!';
            return $reservation;
        } else {
            if ($reservation != null) {
                unset($reservation['Reservation']['modified']);
            } else {
                $reservation['Reservation']['congregation_id'] = $publisher['Publisher']['congregation_id'];
                $reservation['Reservation']['route_id'] = $routeId;
                $reservation['Reservation']['day'] = $reservationDay;
                $reservation['Reservation']['timeslot_id'] = $reservationTimeslot;
            }
            
            $publisherReservation['PublisherReservation']['publisher_id'] = $publisher['Publisher']['id'];

            $reservation = $model->save($reservation);
            
            $publisherReservation['PublisherReservation']['reservation_id'] = $reservation['Reservation']['id'];
            $model2->create();
            $model2->save($publisherReservation);

            $reservation = $model->find('first', array(
                    'conditions' => array(
                        'Reservation.id' => $reservation['Reservation']['id']
                    ),
                    'recursive' => -1
                )
            );
            $reservation['sendMail'] = $sendMail;

            $reservation = $this->addPublisherRouteAndTimeslotToReservation($reservation, $routeId, $reservationTimeslot);
            
            // debug($reservation);
            return $reservation;
        }
    }

    public function deletePublisher($congregationId, $routeId, $reservationDay, $reservationTimeslot, $deletePartners) {
        $model = ClassRegistry::init('Reservation');
        $model2 = ClassRegistry::init('PublisherReservation');

        $reservation = $model->find('first', array(
                'conditions' => array(
                    'Reservation.day' => $reservationDay,
                    'Reservation.timeslot_id' => $reservationTimeslot,
                    'Reservation.congregation_id' => $congregationId,
                    'Reservation.route_id' => $routeId
                ),
                'recursive' => -1
            )
        );

        $sendMail = false;

        if ($reservation != null) {
        	foreach($deletePartners as $deleteId) {
        		$model2->delete($deleteId);
        	}
        	
        	$leftReservations = $model2->find('all', array(
        			'conditions' => array(
        				'PublisherReservation.reservation_id' => $reservation['Reservation']['id']
        			),
        		)
        	);
        	
        	if(empty($leftReservations)) {
	            $model->delete($reservation['Reservation']['id']);
	            $reservation = null;
        	} else {
        		$reservation = $model->find('first', array(
        				'conditions' => array(
        						'Reservation.id' => $reservation['Reservation']['id']
        				),
        				'recursive' => -1
        			)
        		);
				$sendMail = true;
        	}
			$reservation['sendMail'] = $sendMail;
			
			$reservation = $this->addPublisherRouteAndTimeslotToReservation($reservation, $routeId, $reservationTimeslot);
		}        
        // debug($reservation);

        return $reservation;
    }

    public function addGuest($congregationId, $routeId, $reservationDay, $reservationTimeslot, $displayTime, $publisher, $guestname) {
        $model = ClassRegistry::init('Reservation');
        $model2 = ClassRegistry::init('PublisherReservation');

        $reservation = $model->find('first', array(
                'conditions' => array(
                    'Reservation.day' => $reservationDay,
                    'Reservation.timeslot_id' => $reservationTimeslot,
                    'Reservation.congregation_id' => $congregationId,
                    'Reservation.route_id' => $routeId
                ),
                'recursive' => -1
            )
        );

        $sendMail = false;
		$send_mail_when_partner = false;
		
		if ($reservation != null && $reservation['Reservation']['modified'] > $displayTime) {
			// Reservation has been modified -> don't save
			$reservation['error'] = 'Der Termin wurde zwischenzeitlich verändert. Bitte überprüfe deine Buchung!';
			return $reservation;
		} else {
			if ($reservation != null) {
				unset($reservation['Reservation']['modified']);
			} else {
				$reservation['Reservation']['congregation_id'] = $publisher['Publisher']['congregation_id'];
				$reservation['Reservation']['route_id'] = $routeId;
				$reservation['Reservation']['day'] = $reservationDay;
				$reservation['Reservation']['timeslot_id'] = $reservationTimeslot;
			}
			
            $guestPublisher = $this->PublisherDAO->getByName($guestname, $publisher);

            if (!$guestPublisher) {
                $guestPublisher = $this->PublisherDAO->getGuestPublisher();
                $publisherReservation['PublisherReservation']['guestname'] = $guestname;
                $sendMail = true;
            } else if($guestPublisher['Publisher']['send_mail_when_partner']) {
				$send_mail_when_partner = true;
			}

            $publisherReservation['PublisherReservation']['publisher_id'] = $guestPublisher['Publisher']['id'];

            $reservation = $model->save($reservation);
                
            $publisherReservation['PublisherReservation']['reservation_id'] = $reservation['Reservation']['id'];
            $model2->create();
            $model2->save($publisherReservation);

            $reservation = $model->find('first', array(
                    'conditions' => array(
                        'Reservation.id' => $reservation['Reservation']['id']
                    ),
                    'recursive' => -1
                )
            );
            
            $reservation = $this->addPublisherRouteAndTimeslotToReservation($reservation, $routeId, $reservationTimeslot);
        }

        $reservation['sendMail'] = $sendMail;
        if($sendMail) {
        	$reservation['guestName'] = $guestname;
        }
        
		$reservation['send_mail_when_partner'] = $send_mail_when_partner;
		if($send_mail_when_partner) {
			$reservation['GuestPublisher'] = $guestPublisher['Publisher'];
		}
        // debug($reservation);

        return $reservation;
    }
    
    private function addPublisherRouteAndTimeslotToReservation($reservation, $routeId, $reservationTimeslot) {
    	$modelPublisherReservation = ClassRegistry::init('PublisherReservation');
    	$modelRoute = ClassRegistry::init('Route');
    	$modelTimeslot = ClassRegistry::init('Timeslot');
    	 
    	if (array_key_exists('Reservation', $reservation)) {
    		$reservation['PublisherReservation'] = array();
    		$reservation['Publisher'] = array();
    		$publisherReservations = $modelPublisherReservation->find('all', array('conditions' => array('PublisherReservation.reservation_id' => $reservation['Reservation']['id']), 'recursive' => 1));
    		
    		foreach($publisherReservations as $publisherReservation) {
    			array_push($reservation['PublisherReservation'], $publisherReservation['PublisherReservation']);
    			array_push($reservation['Publisher'], $publisherReservation['Publisher']);
    		}
    	}
    	 
    	$route = $modelRoute->find('first', array(
    			'conditions' => array(
    					'Route.id' => $routeId
    			),
    			'recursive' => -1
    		)
    	);
    	$reservation['Route'] = $route['Route'];
    	 
    	$timeslot = $modelTimeslot->find('first', array(
    			'conditions' => array(
    					'Timeslot.id' => $reservationTimeslot
    			),
    			'recursive' => -1
    	)
    			);
    	$reservation['Timeslot'] = $timeslot['Timeslot'];
    	 
    	return $reservation;
    }
	
	public function getMissingReports($publisher) {
		$model = ClassRegistry::init('PublisherReservation');
		
		$allReports = $this->getMissingCongregationReports($publisher);
		
		foreach($allReports as $key => $reservation) {
			$publisherReport = false;
			foreach ($reservation['PublisherReservation'] as $publisherReservation) {
				if($publisherReservation['publisher_id'] == $publisher['Publisher']['id']) {
					$publisherReport = true;
					break;
				}
			}
			if(!$publisherReport) {
				unset($allReports[$key]);
			}		
		}
		return $allReports;
	}
	
	public function getMissingCongregationReports($publisher) {
		$model = ClassRegistry::init('Reservation');

        $result= $model->find('all', array(
			'conditions' => array(
                    'Reservation.day between \'' . $publisher['Congregation']['report_start_date'] . '\' and \'' . date("Y-m-d") . '\'',
                    'Reservation.congregation_id' => $publisher['Congregation']['id'],
					'Reservation.report_necessary' => 1,
					'Reservation.minutes' => null
                ),
            'order' => array('Reservation.day', 'Reservation.timeslot_id'),
            'recursive' => 1
            )
        );
		return $result;
	}
	
	public function getMissingCongregationReportsCount($publisher) {
		$model = ClassRegistry::init('Reservation');
		
		$result= $model->find('count', array(
			'conditions' => array(
					'Reservation.day between \'' . $publisher['Congregation']['report_start_date'] . '\' and \'' . date("Y-m-d") . '\'',
					'Reservation.congregation_id' => $publisher['Congregation']['id'],
					'Reservation.report_necessary' => 1,
					'Reservation.minutes' => null
			),
			'order' => array('Reservation.day', 'Reservation.timeslot_id'),
			'recursive' => -1
			)
		);
		return $result;
	}
	
	public function getDeclinedCongregationReports($publisher) {
		$model = ClassRegistry::init('Reservation');

        $result= $model->find('all', array(
			'conditions' => array(
                    'Reservation.day between \'' . $publisher['Congregation']['report_start_date'] . '\' and \'' . date("Y-m-d") . '\'',
                    'Reservation.congregation_id' => $publisher['Congregation']['id'],
					'Reservation.report_necessary' => 0,
					'Reservation.no_report_reason !=' => null
                ),
            'order' => array('Reservation.day', 'Reservation.timeslot_id'),
            'recursive' => 0
            )
        );
		return $result;
	}
	
	public function getGivenCongregationReports($publisher) {
		$model = ClassRegistry::init('Reservation');

        $result= $model->find('all', array(
			'conditions' => array(
                    'Reservation.day between \'' . $publisher['Congregation']['report_start_date'] . '\' and \'' . date("Y-m-d") . '\'',
                    'Reservation.congregation_id' => $publisher['Congregation']['id'],
					'Reservation.report_necessary' => 1,
					'Reservation.minutes !=' => null
                ),
            'order' => array('Reservation.day', 'Reservation.timeslot_id'),
            'recursive' => 0
            )
        );
		return $result;
	}
	
	public function getGivenCongregationReportsPerMonth($publisher, $date) {
		$model = ClassRegistry::init('Reservation');

        $result= $model->find('all', array(
			'conditions' => array(
                    'Reservation.day between \'' . $date . '\' and \'' . date("Y-m-t", strtotime($date)) . '\'',
                    'Reservation.congregation_id' => $publisher['Congregation']['id'],
					'Reservation.report_necessary' => 1,
					'Reservation.minutes !=' => null
                ),
            'order' => array('Reservation.day', 'Reservation.timeslot_id'),
            'recursive' => 0
            )
        );
		return $result;
	}
	
	public function getMissingCongregationReportsPerMonth($publisher, $date) {
		$model = ClassRegistry::init('Reservation');

        $result= $model->find('all', array(
			'conditions' => array(
                    'Reservation.day between \'' . $date . '\' and \'' . date("Y-m-t", strtotime($date)) . '\'',
                    'Reservation.congregation_id' => $publisher['Congregation']['id'],
					'Reservation.report_necessary' => 1,
					'Reservation.minutes' => null
                ),
            'order' => array('Reservation.day', 'Reservation.timeslot_id'),
            'recursive' => 0
            )
        );
		return $result;
	}
	
	public function getDeclinedCongregationReportsPerMonth($publisher, $date) {
		$model = ClassRegistry::init('Reservation');

        $result= $model->find('all', array(
			'conditions' => array(
                    'Reservation.day between \'' . $date . '\' and \'' . date("Y-m-t", strtotime($date)) . '\'',
                    'Reservation.congregation_id' => $publisher['Congregation']['id'],
					'Reservation.report_necessary' => 0,
					'Reservation.no_report_reason' => null
                ),
            'order' => array('Reservation.day', 'Reservation.timeslot_id'),
            'recursive' => 0
            )
        );
		return $result;
	}
	
	public function getAllReservationsPerMonth($publisher, $date) {
		$model = ClassRegistry::init('Reservation');

        $result= $model->find('all', array(
			'conditions' => array(
                    'Reservation.day between \'' . $date . '\' and \'' . date("Y-m-t", strtotime($date)) . '\'',
                    'Reservation.congregation_id' => $publisher['Congregation']['id']
                ),
            'order' => array('Reservation.day', 'Reservation.timeslot_id'),
            'recursive' => 0
            )
        );
		return $result;
	}
}
