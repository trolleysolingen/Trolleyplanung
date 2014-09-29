<?php

App::uses('Component', 'Controller');

class ReservationDAOComponent extends Component {

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
}
