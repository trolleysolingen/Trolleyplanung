<?php

App::uses('Component', 'Controller');

class ReservationDAOComponent extends Component {

    public $components = array('PublisherDAO', 'MailService');
	
	public function getByRealId($id) {
        $model = ClassRegistry::init('Reservation');

        $result= $model->find('first', array('conditions' => array('Reservation.id' => $id), 'recursive' => 1));

        return $result;
    }

    public function getReservationsInTimeRange($mondayThisWeek, $congregationId) {
        $model = ClassRegistry::init('Reservation');

        $lastDateOnView = strtotime('monday this week +' . Configure::read('DISPLAYED_WEEKS'). ' week');

        $result= $model->find('all', array(
            'conditions' => array(
                'Reservation.day between STR_TO_DATE(?, \'%d.%m.%Y\') and STR_TO_DATE(?, \'%d.%m.%Y\')' =>
                    array(date("d.m.Y", $mondayThisWeek), date("d.m.Y", $lastDateOnView)),
                'Reservation.congregation_id' => $congregationId
            ),
            'order' => array('Reservation.day', 'Reservation.timeslot_id'),
            'recursive' => 1
            )
        );

        return $result;
    }

    public function addPublisher($congregationId, $reservationDay, $reservationTimeslot, $displayTime, $publisher) {
        $model = ClassRegistry::init('Reservation');

        $reservation = $model->find('first', array(
                'conditions' => array(
                    'Reservation.day' => $reservationDay,
                    'Reservation.timeslot_id' => $reservationTimeslot,
                    'Reservation.congregation_id' => $congregationId
                ),
            'recursive' => 0
            )
        );

        $sendMail = false;
        if ($reservation != null && $reservation['Reservation']['modified'] > $displayTime) {
            // Reservation has been modified -> don't save
            $reservation['error'] = 'Der Termin wurde zwischenzeitlich verändert. Bitte überprüfe deine Buchung!';
            return $reservation;
        } else {
            if ($reservation != null) {
                if ($reservation['Reservation']['publisher1_id'] == null) {
                    $reservation['Reservation']['publisher1_id'] = $publisher['Publisher']['id'];
                } else if ($reservation['Reservation']['publisher2_id'] == null) {
                    $reservation['Reservation']['publisher2_id'] = $publisher['Publisher']['id'];
                    $sendMail = true;
                }
                unset($reservation['Reservation']['modified']);
            } else {
                $reservation['Reservation']['congregation_id'] = $publisher['Publisher']['congregation_id'];
                $reservation['Reservation']['day'] = $reservationDay;
                $reservation['Reservation']['timeslot_id'] = $reservationTimeslot;
                $reservation['Reservation']['publisher1_id'] = $publisher['Publisher']['id'];
            }

            $reservation = $model->save($reservation);

            $reservation = $model->find('first', array(
                    'conditions' => array(
                        'Reservation.id' => $reservation['Reservation']['id']
                    ),
                    'recursive' => 0
                )
            );
            $reservation['sendMail'] = $sendMail;

            // debug($reservation);
            return $reservation;
        }
    }


    public function deletePublisher($congregationId, $reservationDay, $reservationTimeslot, $publisher, $deleteBoth) {
        $model = ClassRegistry::init('Reservation');

        $reservation = $model->find('first', array(
                'conditions' => array(
                    'Reservation.day' => $reservationDay,
                    'Reservation.timeslot_id' => $reservationTimeslot,
                    'Reservation.congregation_id' => $congregationId
                ),
                'recursive' => -1
            )
        );

        $sendMail = false;

        if ($reservation != null) {

            if (!$deleteBoth &&
                $reservation['Reservation']['publisher1_id'] != null &&
                $reservation['Reservation']['publisher2_id'] != null ) {

                if ($reservation['Reservation']['publisher1_id'] == $publisher['Publisher']['id']) {
                    // delete publisher1 and put publisher2 to publisher1
                    $reservation['Reservation']['publisher1_id'] = $reservation['Reservation']['publisher2_id'];
                    $reservation['Reservation']['publisher2_id'] = null;
                } else if ($reservation['Reservation']['publisher2_id'] == $publisher['Publisher']['id']) {
                    $reservation['Reservation']['publisher2_id'] = null;
                }
                unset($reservation['Reservation']['modified']);

                $reservation = $model->save($reservation);

                $reservation = $model->find('first', array(
                        'conditions' => array(
                            'Reservation.id' => $reservation['Reservation']['id']
                        ),
                        'recursive' => 0
                    )
                );
                $sendMail = true;
            } else {
                $model->delete($reservation['Reservation']['id']);
                $reservation = null;
            }
        }

        $reservation['sendMail'] = $sendMail;
        // debug($reservation);

        return $reservation;
    }

    public function addGuest($congregationId, $reservationDay, $reservationTimeslot, $displayTime, $publisher, $guestname) {
        $model = ClassRegistry::init('Reservation');

        $reservation = $model->find('first', array(
                'conditions' => array(
                    'Reservation.day' => $reservationDay,
                    'Reservation.timeslot_id' => $reservationTimeslot,
                    'Reservation.congregation_id' => $congregationId
                ),
                'recursive' => 0
            )
        );

        $sendMail = false;
		$send_mail_when_partner = false;
        if ($reservation != null) {

            if ($reservation['Reservation']['modified'] > $displayTime) {
                // Reservation has been modified -> don't save
                $reservation['error'] = 'Der Termin wurde zwischenzeitlich verändert. Bitte überprüfe deine Buchung!';
                return $reservation;
            } else {
                $guestPublisher = $this->PublisherDAO->getByName($guestname, $publisher);

                if (!$guestPublisher) {
                    $guestPublisher = $this->PublisherDAO->getGuestPublisher();
                    $reservation['Reservation']['guestname'] = $guestname;
                    $sendMail = true;
                } else if($guestPublisher['Publisher']['send_mail_when_partner']) {
					$send_mail_when_partner = true;
				}

                $reservation['Reservation']['publisher2_id'] = $guestPublisher['Publisher']['id'];
                unset($reservation['Reservation']['modified']);

                $reservation = $model->save($reservation);

                $reservation = $model->find('first', array(
                        'conditions' => array(
                            'Reservation.id' => $reservation['Reservation']['id']
                        ),
                        'recursive' => 0
                    )
                );
            }
        }

        $reservation['sendMail'] = $sendMail;
		$reservation['send_mail_when_partner'] = $send_mail_when_partner;
        // debug($reservation);

        return $reservation;
    }
	
	public function getMissingReports($publisher) {
		$model = ClassRegistry::init('Reservation');

        $result= $model->find('all', array(
			'conditions' => array(
					'OR' => array(
						'AND' => array(
							array('Reservation.publisher1_id' => $publisher['Publisher']['id']),
							array('Reservation.day between \'' . $publisher['Congregation']['report_start_date'] . '\' and \'' . date("Y-m-d") . '\''),
							array('Reservation.report_necessary' => 1),
							array('Reservation.books' => null)
						),
						'OR' => array(
							array(
								'AND' => array(
									array('Reservation.publisher2_id' => $publisher['Publisher']['id']),
									array('Reservation.day between \'' . $publisher['Congregation']['report_start_date'] . '\' and \'' . date("Y-m-d") . '\''),
									array('Reservation.report_necessary' => 1),
									array('Reservation.books' => null)
								)
							)
						)
					)
				),
            'order' => array('Reservation.day', 'Reservation.timeslot_id'),
            'recursive' => 0
            )
        );
		return $result;
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
            'recursive' => 0
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
            'recursive' => 0
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
                    'Reservation.day between \'' . $date . '\' and \'' . date('Y-m-t') . '\'',
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
                    'Reservation.day between \'' . $date . '\' and \'' . date("Y-m-t") . '\'',
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
                    'Reservation.day between \'' . $date . '\' and \'' . date("Y-m-t") . '\'',
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
                    'Reservation.day between \'' . $date . '\' and \'' . date("Y-m-t") . '\'',
                    'Reservation.congregation_id' => $publisher['Congregation']['id']
                ),
            'order' => array('Reservation.day', 'Reservation.timeslot_id'),
            'recursive' => 0
            )
        );
		return $result;
	}
}
