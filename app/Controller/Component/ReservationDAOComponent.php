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
            'recursive' => 2
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
                    'recursive' => 2
                )
            );
            $reservation['sendMail'] = $sendMail;

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
                'recursive' => 2
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
        				'recursive' => 2
        			)
        		);
        		$sendMail = true;
        	}
        	$reservation['sendMail'] = $sendMail;
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
                'recursive' => 2
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
                    'recursive' => 2
                )
            );
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
	
	public function getMissingReports($publisher) {
		$model = ClassRegistry::init('PublisherReservation');
		
		$allReports = $this->getMissingCongregationReports($publisher);
		
		foreach($allReports as $key => $reservation) {
			$publisherReservation = $model->find('first', array(
					'conditions' => array(
						'PublisherReservation.reservation_id' => $reservation['Reservation']['id'],
						'PublisherReservation.publisher_id' => $publisher['Publisher']['id']
					),
				)
			);
			
			if(empty($publisherReservation)) {
				unset($allReports[$key]);
			}
		}
		return $allReports;
	}
	
	public function getGivenReports($publisher) {
		$model = ClassRegistry::init('Reservation');

        $result= $model->find('all', array(
			'conditions' => array(
                    'Reservation.day between \'' . $publisher['Congregation']['report_start_date'] . '\' and \'' . date("Y-m-d") . '\'',
                    'Reservation.reporter_id' => $publisher['Publisher']['id'],
					'Reservation.report_necessary' => 1,
					'Reservation.minutes !=' => null
                ),
            'order' => array('Reservation.day', 'Reservation.timeslot_id'),
            'recursive' => 2
            )
        );
		return $result;
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
            'recursive' => 2
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
