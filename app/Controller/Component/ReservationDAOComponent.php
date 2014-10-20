<?php

App::uses('Component', 'Controller');

class ReservationDAOComponent extends Component {

    public $components = array('PublisherDAO', 'MailService');

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

            } else {
                $model->delete($reservation['Reservation']['id']);
                $reservation = null;
            }
        }

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

        // debug($reservation);

        return $reservation;
    }

}
